<?php

namespace App\Http\Requests;

use App\Support\Localization;
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

    public function update($new_data, Taxon $taxon, $country_ref)
    {
        return DB::transaction(function () use ($taxon, $new_data, $country_ref) {
            $oldData = $taxon->load([
                'parent', 'stages', 'conservationLegislations', 'redLists',
                'conservationDocuments',
            ])->toArray();

            $taxon->update(array_merge(
                array_map('trim', Arr::only($new_data, ['name', 'rank'])),
                Arr::only($new_data, ['fe_old_id', 'fe_id', 'author', 'restricted',
                    'allochthonous', 'invasive', 'uses_atlas_codes',]),
                ['taxonomy_id' => $new_data['id']]
            ));

            $this->syncNamesAndDescriptions($new_data, $taxon);

            $this->syncRelations($new_data, $taxon, $country_ref);

            $this->logUpdatedActivity($taxon, $oldData, $new_data['reason']);

            return $taxon;
        });
    }

    protected function syncRelations($data, Taxon $taxon, $country_ref)
    {
        $taxon->stages()->sync($this->getStageIds(Arr::only($data, ['stages'])));
        $taxon->conservationLegislations()->sync($this->getConservationLegislationIds(Arr::only($data, ['conservation_legislations']), $country_ref['legs']));
        $taxon->conservationDocuments()->sync($this->getConservationDocumentIds(Arr::only($data, ['conservation_documents']), $country_ref['docs']));
        $taxon->redLists()->sync($this->getRedListIds(Arr::only($data, ['red_lists']), $country_ref['redLists']));
    }

    protected function logUpdatedActivity(Taxon $taxon, $oldData, $reason)
    {
        activity()->performedOn($taxon)
            ->causedBy($this->user() || 1)
            ->withProperty('old', $this->getChangedData($taxon, $oldData))
            ->withProperty('reason', $reason)
            ->log('updated');
    }

    protected function getStageIds(array $data)
    {
        $stage_ids = [];
        foreach ($data['stages'] as $stage) {
            $stage_ids[] = $stage['id'];
        }

        return $stage_ids;
    }

    protected function getConservationLegislationIds(array $data, $legs)
    {
        $conservation_legislation_ids = [];
        foreach ($data['conservation_legislations'] as $conservation_legislation) {
            $conservation_legislation_ids[] = $legs[$conservation_legislation['id']];
        }

        return $conservation_legislation_ids;
    }

    protected function getConservationDocumentIds(array $data, $docs)
    {
        $conservation_document_ids = [];
        foreach ($data['conservation_documents'] as $conservation_document) {
            $conservation_document_ids[] = $docs[$conservation_document['id']];
        }

        return $conservation_document_ids;
    }

    protected function getRedListIds(array $data, $redLists)
    {
        $red_list_map = [];
        foreach ($data['red_lists'] as $red_list) {
            $red_list_map[$redLists[$red_list['id']]] = ['category' => $red_list['pivot']['category']];
        }

        return $red_list_map;
    }

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
                    $data[$key] = ['value' => $value, 'label' => 'taxonomy.'.$value];
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

    protected function stagesAreChanged($taxon, $oldValue)
    {
        $taxon->load('stages');

        return $oldValue->count() !== $taxon->stages->count()
            || ($oldValue->isNotEmpty() && $taxon->stages->isNotEmpty()
                && $oldValue->pluck('id')->diff($taxon->stages->pluck('id'))->isNotEmpty());
    }

    protected function translationIsChanged($translatedAttribute, $oldValue, $value)
    {
        $old = $oldValue->mapWithKeys(function ($translation) use ($translatedAttribute) {
            return [$translation['locale'] => $translation[$translatedAttribute] ?? null];
        });


        $new = $value->mapWithKeys(function ($translation) use ($translatedAttribute) {
            return [$translation->locale => $translation->{$translatedAttribute}];
        });

        return ! $old->diffAssoc($new)->isEmpty() || ! $new->diffAssoc($old)->isEmpty();
    }

    protected function conservationLegislationsAreChanged($taxon, $oldValue)
    {
        $taxon->load('conservationLegislations');

        return $oldValue->count() !== $taxon->conservationLegislations->count()
            || ($oldValue->isNotEmpty() && $taxon->conservationLegislations->isNotEmpty()
                && $oldValue->pluck('id')->diff($taxon->conservationLegislations->pluck('id'))->isNotEmpty());
    }

    protected function conservationDocumentsAreChanged($taxon, $oldValue)
    {
        $taxon->load('conservationDocuments');

        return $oldValue->count() !== $taxon->conservationDocuments->count()
            || ($oldValue->isNotEmpty() && ! $taxon->conservationDocuments->isNotEmpty()
                && $oldValue->pluck('id')->diff($taxon->conservationDocuments->pluck('id'))->isNotEmpty());
    }

    protected function redListsAreChanged($taxon, $oldValue)
    {
        $taxon->load('redLists');

        return $oldValue->count() !== $taxon->redLists->count()
            || (! $oldValue->isEmpty() && ! $taxon->redLists->isEmpty()
                && $oldValue->pluck('id')->diff($taxon->redLists->pluck('id'))->isNotEmpty()
                || $oldValue->filter(function ($oldRedList) use ($taxon) {
                    return $taxon->redLists->contains(function ($redList) use ($oldRedList) {
                        return $redList->id === $oldRedList['id']
                            && $redList->pivot->category === Arr::get($oldRedList, 'pivot.category');
                    });
                })->count() !== $oldValue->count());
    }

    private function syncNamesAndDescriptions($new_data, Taxon $taxon)
    {
        $data = [
            'description' => [],
            'native_name' => [],
        ];

        $translations = Arr::only($new_data, ['translations']);
        foreach ($translations['translations'] as $trans) {
            $data['description'][$trans['locale']] = $trans['description'];
            $data['native_name'][$trans['locale']] = $trans['native_name'];
        }

        $taxon->update(Localization::transformTranslations(Arr::only(
            $data,
            ['description', 'native_name']
        )));
    }
}
