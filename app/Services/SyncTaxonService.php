<?php

namespace App\Services;

use App\Support\Localization;
use App\Synonym;
use App\Taxon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SyncTaxonService
{
    /**
     * Update an existing local taxon with data received from the Taxonomy server.
     *
     * @throws \Throwable
     */
    public function updateTaxon(array $updatedData, Taxon $taxon, array $countryRef): Taxon
    {
        return DB::transaction(function () use ($taxon, $updatedData, $countryRef) {
            $oldData = $taxon->load([
                'parent', 'stages', 'conservationLegislations', 'redLists',
                'conservationDocuments', 'synonyms',
            ])->toArray();

            $parentId = $this->resolveParentId($taxon, $updatedData, $oldData, $countryRef);
            $attributes = $this->resolveCountryAttributes($updatedData, $countryRef);

            $taxon->update(array_merge(
                array_map('trim', Arr::only($updatedData, ['name', 'rank'])),
                Arr::only($updatedData, ['fe_old_id', 'fe_id', 'author', 'uses_atlas_codes']),
                ['taxonomy_id' => Arr::get($updatedData, 'id'), 'parent_id' => $parentId],
                $attributes
            ));

            $this->syncNamesAndDescriptions($updatedData, $taxon);
            $this->syncRelations($updatedData, $taxon, $countryRef);
            $this->syncSynonyms($updatedData, $taxon);

            if ($parentId) {
                $taxon->rebuildAncestryOnDescendants();
            }

            return $taxon;
        });
    }

    /**
     * Create a new local taxon from data received from the Taxonomy server.
     *
     * @throws \Throwable
     */
    public function createTaxon(array $newTaxon, array $countryRef): Taxon
    {
        return DB::transaction(function () use ($newTaxon, $countryRef) {
            $parentId = $this->resolveOrCreateParentId($newTaxon, $countryRef);
            $attributes = $this->resolveCountryAttributes($newTaxon, $countryRef);

            $taxon = Taxon::findByRankNameAndAncestor($newTaxon['name'], $newTaxon['rank']);

            if (! $taxon) {
                $taxon = Taxon::create(array_merge(
                    array_map('trim', Arr::only($newTaxon, ['name', 'rank'])),
                    Arr::only($newTaxon, ['fe_id', 'author', 'fe_old_id', 'uses_atlas_codes']),
                    ['taxonomy_id' => $newTaxon['id'], 'parent_id' => $parentId],
                    Localization::transformTranslations(Arr::only($newTaxon, ['description', 'native_name'])),
                    $attributes
                ));
            } else {
                $taxon->update(array_merge(
                    array_map('trim', Arr::only($newTaxon, ['name', 'rank'])),
                    Arr::only($newTaxon, ['fe_old_id', 'fe_id', 'author', 'uses_atlas_codes']),
                    ['taxonomy_id' => Arr::get($newTaxon, 'id')],
                    $attributes
                ));
            }

            $taxon->load([
                'parent', 'stages', 'conservationLegislations', 'redLists',
                'conservationDocuments', 'synonyms',
            ]);

            $this->syncRelations($newTaxon, $taxon, $countryRef);
            $this->syncSynonyms($newTaxon, $taxon);
            $taxon->rebuildAncestryOnDescendants();

            return $taxon;
        });
    }

    /**
     * Resolve parent_id for an update operation.
     */
    private function resolveParentId(Taxon $taxon, array $updatedData, array $oldData, array $countryRef): ?int
    {
        if (! empty($updatedData['parent'])) {
            $parent = $this->findOrCreateParent($updatedData['parent'], $countryRef);

            return $parent?->id;
        }

        // If there was a parent before but the server sent none, detach it
        if (isset($oldData['parent'])) {
            return null;
        }

        return $taxon->parent_id;
    }

    /**
     * Resolve parent_id for a create operation, creating the parent locally if needed.
     */
    private function resolveOrCreateParentId(array $taxonData, array $countryRef): ?int
    {
        if (empty($taxonData['parent'])) {
            return null;
        }

        $parent = $this->findOrCreateParent($taxonData['parent'], $countryRef);

        return $parent?->id;
    }

    /**
     * Find an existing local parent taxon or create it and sync it back to the Taxonomy server.
     * The HTTP sync call is intentionally kept outside any surrounding DB transaction.
     */
    private function findOrCreateParent(array $parentData, array $countryRef): ?Taxon
    {
        $ancestorsNames = $parentData['ancestors_names'] ?? null;
        $ancestor = null;

        if (! empty($ancestorsNames)) {
            $names = explode(',', $ancestorsNames);
            $ancestor = ! empty($names[0]) ? $names[0].'%' : null;
        }

        $parent = Taxon::findByRankNameAndAncestor(
            $parentData['name'],
            $parentData['rank'],
            $ancestor
        );

        if ($parent) {
            return $parent;
        }

        // Create the parent locally first (inside its own transaction), then sync
        $parent = DB::transaction(fn () => Taxon::create(array_merge(
            array_map('trim', Arr::only($parentData, ['name', 'rank'])),
            Arr::only($parentData, [
                'fe_id', 'author', 'fe_old_id', 'restricted',
                'allochthonous', 'invasive', 'uses_atlas_codes',
            ]),
            Localization::transformTranslations(Arr::only($parentData, ['description', 'native_name']))
        )));

        // HTTP call outside the transaction to avoid holding the connection open
        app(TaxonomyService::class)->syncParent($parent);

        $parent->rebuildAncestryOnDescendants();

        return $parent;
    }

    /**
     * Resolve restricted/allochthonous/invasive from country_ref if available, falling back to taxon data.
     */
    private function resolveCountryAttributes(array $data, array $countryRef): array
    {
        $attributes = [];

        foreach (['restricted', 'allochthonous', 'invasive'] as $attribute) {
            $attributes[$attribute] = ! is_null($countryRef[$attribute] ?? null)
                ? $countryRef[$attribute]
                : $data[$attribute];
        }

        return $attributes;
    }

    /**
     * Sync all relational data (stages, legislations, documents, red lists) for a taxon.
     */
    protected function syncRelations(array $data, Taxon $taxon, array $countryRef): void
    {
        $taxon->stages()->sync(
            $this->getStageIds(Arr::only($data, ['stages']))
        );
        $taxon->conservationLegislations()->sync(
            $this->getConservationLegislationIds(Arr::only($data, ['conservation_legislations']), $countryRef['legs'] ?? [])
        );
        $taxon->conservationDocuments()->sync(
            $this->getConservationDocumentIds(Arr::only($data, ['conservation_documents']), $countryRef['docs'] ?? [])
        );
        $taxon->redLists()->sync(
            $this->getRedListIds(Arr::only($data, ['red_lists']), $countryRef['redLists'] ?? [])
        );
    }

    /**
     * Sync translated names and descriptions from Taxonomy data onto the local taxon.
     */
    private function syncNamesAndDescriptions(array $newData, Taxon $taxon): void
    {
        $data = ['description' => [], 'native_name' => []];

        foreach (Arr::get($newData, 'translations', []) as $trans) {
            $locale = Arr::get($trans, 'locale');
            if ($locale) {
                $data['description'][$locale] = Arr::get($trans, 'description');
                $data['native_name'][$locale] = Arr::get($trans, 'native_name');
            }
        }

        $transformed = Localization::transformTranslations(Arr::only($data, ['description', 'native_name']));

        if (! empty($transformed)) {
            $taxon->update($transformed);
        }
    }

    /**
     * Replace all synonyms on a taxon with the ones received from Taxonomy.
     */
    private function syncSynonyms(array $newData, Taxon $taxon): void
    {
        Synonym::whereIn('id', collect($taxon->synonyms)->pluck('id'))->delete();

        $synonymsToInsert = array_map(fn ($s) => [
            'name' => $s['name'],
            'author' => $s['author'],
            'taxon_id' => $taxon->id,
        ], Arr::get($newData, 'synonyms', []));

        if (! empty($synonymsToInsert)) {
            Synonym::insert($synonymsToInsert);
        }
    }

    protected function getStageIds(array $data): array
    {
        return collect($data['stages'])
            ->filter(fn ($s) => \App\Stage::where('id', $s['id'])->exists())
            ->pluck('id')
            ->all();
    }

    protected function getConservationLegislationIds(array $data, array $legs): array
    {
        $ids = [];
        foreach ($data['conservation_legislations'] as $item) {
            if (Arr::exists($legs, $item['id']) && \App\ConservationLegislation::where('id', $legs[$item['id']])->exists()) {
                $ids[] = $legs[$item['id']];
            }
        }

        return $ids;
    }

    protected function getConservationDocumentIds(array $data, array $docs): array
    {
        $ids = [];
        foreach ($data['conservation_documents'] as $item) {
            if (Arr::exists($docs, $item['id']) && \App\ConservationDocument::where('id', $docs[$item['id']])->exists()) {
                $ids[] = $docs[$item['id']];
            }
        }

        return $ids;
    }

    protected function getRedListIds(array $data, array $redLists): array
    {
        $map = [];
        foreach ($data['red_lists'] as $item) {
            if (Arr::exists($redLists, $item['id']) && \App\RedList::where('id', $redLists[$item['id']])->exists()) {
                $map[$redLists[$item['id']]] = ['category' => $item['pivot']['category']];
            }
        }

        return $map;
    }
}
