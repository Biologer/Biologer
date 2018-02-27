<?php

namespace App\Http\Requests;

use App\Stage;
use App\Taxon;
use App\RedList;
use App\ConservationDocument;
use App\Support\Localization;
use Illuminate\Validation\Rule;
use App\ConservationLegislation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxon extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::check('update', $this->taxon);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('taxa', 'name')->ignore($this->taxon->id)],
            'parent_id' => ['nullable', 'exists:taxa,id'],
            'rank' => ['required', Rule::in(array_keys(Taxon::RANKS))],
            'author' => ['nullable', 'string'],
            'fe_old_id' => ['nullable', 'integer'],
            'fe_id' => ['nullable'],
            'restricted' => ['boolean'],
            'allochthonous' => ['boolean'],
            'invasive' => ['boolean'],
            'stages_ids' => ['nullable', 'array'],
            'stages_ids.*' => ['required', Rule::in(Stage::pluck('id')->all())],
            'conservation_legislations_ids' => [
                'nullable', 'array', Rule::in(ConservationLegislation::pluck('id')->all()),
            ],
            'conservation_documents_ids' => [
                'nullable', 'array', Rule::in(ConservationDocument::pluck('id')->all()),
            ],
            'red_lists_data' => ['nullable', 'array'],
            'red_lists_data.*' => ['array'],
            'red_lists_data.*.red_list_id' => [
                'required',
                Rule::in(RedList::pluck('id')->all()),
            ],
            'red_lists_data.*.category' => [
                'required',
                Rule::in(RedList::CATEGORIES),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'parent_id' => 'parent',
        ];
    }

    /**
     * Update taxon using request data.
     *
     * @return \App\Taxon
     */
    protected function updateTaxon(Taxon $taxon)
    {
        return tap($taxon)->update(array_merge($this->only([
            'name', 'parent_id', 'rank', 'fe_old_id', 'fe_id', 'restricted',
            'allochthonous', 'invasive', 'author',
        ]), Localization::transformTranslations($this->only([
            'description', 'native_name',
        ]))));
    }

    /**
     * Map red list data to format required to store the value.
     *
     * @param  array  $redListsData
     * @return array
     */
    protected function mapRedListsData($redListsData = [])
    {
        return collect($redListsData)->mapWithKeys(function ($item) {
            return [$item['red_list_id'] => ['category' => $item['category']]];
        })->all();
    }

    /**
     * Sync taxon relations.
     *
     * @param  \App\Taxon  $taxon
     * @return void
     */
    protected function syncRelations(Taxon $taxon)
    {
        $taxon->stages()->sync($this->input('stages_ids', []));
        $taxon->conservationLegislations()->sync($this->input('conservation_legislations_ids', []));
        $taxon->conservationDocuments()->sync($this->input('conservation_documents_ids', []));
        $taxon->redLists()->sync(
            $this->mapRedListsData($this->input('red_lists_data', []))
        );
    }

    /**
     * Store the information.
     *
     * @param  \App\Taxon  $taxon
     * @return \App\Taxon
     */
    public function save(Taxon $taxon)
    {
        return tap($this->updateTaxon($taxon), function ($taxon) {
            $this->syncRelations($taxon);
        });
    }
}
