<?php

namespace App\Http\Requests;

use App\Http\Controllers\Admin\TaxonomyController;
use App\Support\Localization;
use App\Synonym;
use App\Taxon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SyncTaxon extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * @param  array $updated_data
     * @param  Taxon $taxon
     * @param  array $country_ref
     * @return Taxon
     */
    public function updateTaxon(array $updated_data, Taxon $taxon, array $country_ref): Taxon
    {
        return DB::transaction(function () use ($taxon, $updated_data, $country_ref) {

            $oldData = $taxon->load([
                'parent', 'stages', 'conservationLegislations', 'redLists',
                'conservationDocuments', 'synonyms',
            ])->toArray();

            $parentId = $taxon->parent_id;

            if (!empty($updated_data['parent'])) {
                $ancestors_names = $updated_data['parent']['ancestors_names'] ?? null;
                $ancestor = null;

                if (!empty($ancestors_names)) {
                    $names = explode(',', $ancestors_names);
                    $ancestor = !empty($names[0]) ? $names[0] . '%' : null;
                }
                $parent = Taxon::findByRankNameAndAncestor(
                    $updated_data['parent']['name'],
                    $updated_data['parent']['rank'],
                    $ancestor
                );

                if (!$parent) {
                    $parent = $this->createAndGetParent($updated_data['parent'], $country_ref);
                }

                $parentId = $parent ? $parent->id : null;
            } else {
                if (isset($oldData['parent'])) {
                    $parentId = null;
                }
            }

            $taxon->update(array_merge(
                array_map('trim', Arr::only($updated_data, ['name', 'rank'])),
                Arr::only($updated_data, [
                        'fe_old_id', 'fe_id', 'author', 'restricted',
                        'allochthonous', 'invasive', 'uses_atlas_codes',
                ]),
                ['taxonomy_id' => Arr::get($updated_data, 'id'), 'parent_id' => $parentId],
            ));

            $this->syncNamesAndDescriptions($updated_data, $taxon);
            $this->syncRelations($updated_data, $taxon, $country_ref);
            $this->syncSynonym($updated_data, $taxon);

            // We are currently not logging any changes in local database.
            // $this->logUpdatedActivity($taxon, $oldData, $new_data['reason']);

            return $taxon;
        });
    }

    /**
     * @param  array $new_taxon
     * @param  array $country_ref
     * @return Taxon
     */
    public function createTaxon(array $new_taxon, array $country_ref): Taxon
    {
        return DB::transaction(function () use ($new_taxon, $country_ref) {
            $parentId = null;

            if (!empty($new_taxon['parent'])) {
                $ancestors_names = $new_taxon['parent']['ancestors_names'] ?? null;
                $ancestor = null;

                if (!empty($ancestors_names)) {
                    $names = explode(',', $ancestors_names);
                    $ancestor = !empty($names[0]) ? $names[0] . '%' : null;
                }

                $parent = Taxon::findByRankNameAndAncestor(
                    $new_taxon['parent']['name'],
                    $new_taxon['parent']['rank'],
                    $ancestor
                );

                if (!$parent) {
                    $parent = $this->createAndGetParent($new_taxon['parent'], $country_ref);
                }

                $parentId = $parent->id;
            }

            $taxon = Taxon::findByRankNameAndAncestor($new_taxon['name'], $new_taxon['rank']);

            if (!$taxon) {
                $taxon = Taxon::create(array_merge(
                    array_map('trim', Arr::only($new_taxon, (['name', 'rank']))),
                    Arr::only($new_taxon, ([
                        'fe_id', 'author', 'fe_old_id', 'restricted', 'allochthonous', 'invasive', 'uses_atlas_codes',
                    ])),
                    ['taxonomy_id' => $new_taxon['id'], 'parent_id' => $parentId],
                    Localization::transformTranslations(Arr::only($new_taxon, ([
                        'description', 'native_name',
                    ])))
                ));
            } else {
                $taxon->update(array_merge(
                    array_map('trim', Arr::only($new_taxon, ['name', 'rank'])),
                    Arr::only($new_taxon, ['fe_old_id', 'fe_id', 'author', 'restricted',
                        'allochthonous', 'invasive', 'uses_atlas_codes',]),
                    ['taxonomy_id' => Arr::get($new_taxon, 'id')]
                ));
            }

            $taxon->load([
                'parent', 'stages', 'conservationLegislations', 'redLists',
                'conservationDocuments', 'synonyms',
            ]);

            $this->syncRelations($new_taxon, $taxon, $country_ref);

            $this->syncSynonym($new_taxon, $taxon);

            return $taxon;
        });
    }

    /**
     * @param  array $new_data
     * @param  array $country_ref
     * @return Taxon
     */
    private function createAndGetParent(array $new_data, array $country_ref): Taxon
    {
        return DB::transaction(function () use ($new_data, $country_ref) {
            $parent = Taxon::create(array_merge(
                array_map('trim', Arr::only($new_data, (['name', 'rank']))),
                Arr::only($new_data, ([
                    'fe_id', 'author', 'fe_old_id', 'restricted', 'allochthonous', 'invasive', 'uses_atlas_codes',
                ])),
                Localization::transformTranslations(Arr::only($new_data, ([
                    'description', 'native_name',
                ])))
            ));

            (new TaxonomyController())->syncParent($parent);

            return $parent;
        });
    }

    /**
     * @param  $data
     * @param  Taxon $taxon
     * @param  array $country_ref
     * @return void
     */
    protected function syncRelations($data, Taxon $taxon, array $country_ref)
    {
        $taxon->stages()->sync($this->getStageIds(Arr::only($data, ['stages'])));
        $taxon->conservationLegislations()->sync($this->getConservationLegislationIds(Arr::only($data, ['conservation_legislations']), $country_ref['legs']));
        $taxon->conservationDocuments()->sync($this->getConservationDocumentIds(Arr::only($data, ['conservation_documents']), $country_ref['docs']));
        $taxon->redLists()->sync($this->getRedListIds(Arr::only($data, ['red_lists']), $country_ref['redLists']));
    }

    /**
     * @param  Taxon $taxon
     * @param  $oldData
     * @param  $reason
     * @return void
     */
    protected function logUpdatedActivity(Taxon $taxon, $oldData, $reason)
    {
        activity()->performedOn($taxon)
            ->causedBy($this->user())
            ->withProperty('old', $this->getChangedData($taxon, $oldData))
            ->withProperty('reason', $reason)
            ->log('updated');
    }

    /**
     * @param  array $data
     * @return array
     */
    protected function getStageIds(array $data): array
    {
        $stage_ids = [];
        foreach ($data['stages'] as $stage) {
            if (\App\Stage::where('id', $stage['id'])->exists()) {
                $stage_ids[] = $stage['id'];
            }
        }

        return $stage_ids;
    }

    /**
     * @param  array $data
     * @param  $legs
     * @return array
     */
    protected function getConservationLegislationIds(array $data, $legs): array
    {
        $conservation_legislation_ids = [];
        foreach ($data['conservation_legislations'] as $conservation_legislation) {
            if (!Arr::exists($legs, $conservation_legislation['id'])) {
                continue;
            }
            if (\App\ConservationLegislation::where('id', $legs[$conservation_legislation['id']])->exists()) {
                $conservation_legislation_ids[] = $legs[$conservation_legislation['id']];
            }
        }

        return $conservation_legislation_ids;
    }

    /**
     * @param  array $data
     * @param  $docs
     * @return array
     */
    protected function getConservationDocumentIds(array $data, $docs): array
    {
        $conservation_document_ids = [];
        foreach ($data['conservation_documents'] as $conservation_document) {
            if (!Arr::exists($docs, $conservation_document['id'])) {
                continue;
            }
            if (\App\ConservationDocument::where('id', $docs[$conservation_document['id']])->exists()) {
                $conservation_document_ids[] = $docs[$conservation_document['id']];
            }
        }

        return $conservation_document_ids;
    }

    /**
     * @param  array $data
     * @param  $redLists
     * @return array
     */
    protected function getRedListIds(array $data, $redLists): array
    {
        $red_list_map = [];
        foreach ($data['red_lists'] as $red_list) {
            if (!Arr::exists($redLists, $red_list['id'])) {
                continue;
            }
            if (\App\RedList::where('id', $redLists[$red_list['id']])->exists()) {
                $red_list_map[$redLists[$red_list['id']]] = ['category' => $red_list['pivot']['category']];
            }
        }

        return $red_list_map;
    }

    /**
     * @param  Taxon $taxon
     * @param  $oldData
     * @return array
     */
    protected function getChangedData(Taxon $taxon, $oldData)
    {
        $changed = array_keys($taxon->getChanges());

        $data = [];
        foreach ($oldData as $key => $value) {
            if ('stages' === $key && $this->stagesAreChanged($taxon, collect($value))) {
                $data[$key] = null;
            } elseif ('conservation_legislations' === $key && $this->conservationLegislationsAreChanged($taxon, collect($value))) {
                $data[$key] = null;
            } elseif ('conservation_documents' === $key && $this->conservationDocumentsAreChanged($taxon, collect($value))) {
                $data[$key] = null;
            } elseif ('red_lists' === $key && $this->redListsAreChanged($taxon, collect($value))) {
                $data[$key] = null;
            } elseif ('translations' === $key) {
                if ($this->translationIsChanged('description', collect($value), $taxon->translations)) {
                    $data['description'] = null;
                }

                if ($this->translationIsChanged('native_name', collect($value), $taxon->translations)) {
                    $data['native_name'] = null;
                }
            } elseif (in_array($key, $changed)) {
                if ('parent_id' === $key) {
                    $data['parent'] = $oldData['parent'] ? $oldData['parent']['name'] : $value;
                } elseif ('rank' === $key) {
                    $data[$key] = ['value' => $value, 'label' => 'taxonomy.' . $value];
                } elseif (in_array($key, ['description', 'native_name'])) {
                    $data[$key] = null;
                } elseif (in_array($key, ['restricted', 'allochthonous', 'invasive', 'uses_atlas_codes'])) {
                    $data[$key] = ['value' => $value, 'label' => $value ? 'Yes' : 'No'];
                } else {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }

    /**
     * @param  $taxon
     * @param  $oldValue
     * @return bool
     */
    protected function stagesAreChanged($taxon, $oldValue)
    {
        $taxon->load('stages');

        return $oldValue->count() !== $taxon->stages->count()
            || ($oldValue->isNotEmpty() && $taxon->stages->isNotEmpty()
                && $oldValue->pluck('id')->diff($taxon->stages->pluck('id'))->isNotEmpty());
    }

    /**
     * @param  $translatedAttribute
     * @param  $oldValue
     * @param  $value
     * @return bool
     */
    protected function translationIsChanged($translatedAttribute, $oldValue, $value)
    {
        $old = $oldValue->mapWithKeys(function ($translation) use ($translatedAttribute) {
            return [$translation['locale'] => $translation[$translatedAttribute] ?? null];
        });


        $new = $value->mapWithKeys(function ($translation) use ($translatedAttribute) {
            return [$translation->locale => $translation->{$translatedAttribute}];
        });

        return !$old->diffAssoc($new)->isEmpty() || !$new->diffAssoc($old)->isEmpty();
    }

    /**
     * @param  $taxon
     * @param  $oldValue
     * @return bool
     */
    protected function conservationLegislationsAreChanged($taxon, $oldValue)
    {
        $taxon->load('conservationLegislations');

        return $oldValue->count() !== $taxon->conservationLegislations->count()
            || ($oldValue->isNotEmpty() && $taxon->conservationLegislations->isNotEmpty()
                && $oldValue->pluck('id')->diff($taxon->conservationLegislations->pluck('id'))->isNotEmpty());
    }

    /**
     * @param  $taxon
     * @param  $oldValue
     * @return bool
     */
    protected function conservationDocumentsAreChanged($taxon, $oldValue)
    {
        $taxon->load('conservationDocuments');

        return $oldValue->count() !== $taxon->conservationDocuments->count()
            || ($oldValue->isNotEmpty() && !$taxon->conservationDocuments->isNotEmpty()
                && $oldValue->pluck('id')->diff($taxon->conservationDocuments->pluck('id'))->isNotEmpty());
    }

    /**
     * @param  $taxon
     * @param  $oldValue
     * @return bool
     */
    protected function redListsAreChanged($taxon, $oldValue)
    {
        $taxon->load('redLists');

        return $oldValue->count() !== $taxon->redLists->count()
            || (!$oldValue->isEmpty() && !$taxon->redLists->isEmpty()
                && $oldValue->pluck('id')->diff($taxon->redLists->pluck('id'))->isNotEmpty()
                || $oldValue->filter(function ($oldRedList) use ($taxon) {
                    return $taxon->redLists->contains(function ($redList) use ($oldRedList) {
                        return $redList->id === $oldRedList['id']
                            && $redList->pivot->category === Arr::get($oldRedList, 'pivot.category');
                    });
                })->count() !== $oldValue->count());
    }

    /**
     * @param  $new_data
     * @param Taxon $taxon
     * @return void
     */
    private function syncNamesAndDescriptions($new_data, Taxon $taxon)
    {
        $data = [
            'description' => [],
            'native_name' => [],
        ];

        $translations = Arr::get($new_data, 'translations', []);

        foreach ($translations as $trans) {
            $locale = Arr::get($trans, 'locale');

            if ($locale) {
                $data['description'][$locale] = Arr::get($trans, 'description', null);
                $data['native_name'][$locale] = Arr::get($trans, 'native_name', null);
            }
        }

        $transformedData = Localization::transformTranslations(Arr::only($data, ['description', 'native_name']));

        if (!empty($transformedData)) {
            $taxon->update($transformedData);
        }
    }

    private function syncSynonym($new_data, Taxon $taxon)
    {
        Synonym::whereIn('id', collect($taxon->synonyms)->pluck('id'))->delete();

        $new_synonyms = Arr::get($new_data, 'synonyms', []);

        $synonymsToInsert = [];
        foreach ($new_synonyms as $synonym) {
            $synonymsToInsert[] = [
                'name' => $synonym['name'],
                'author' => $synonym['author'],
                'taxon_id' => $taxon->id
            ];
        }

        if (!empty($synonymsToInsert)) {
            Synonym::insert($synonymsToInsert);
        }
    }

}
