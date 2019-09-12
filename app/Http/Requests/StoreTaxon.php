<?php

namespace App\Http\Requests;

use App\ConservationDocument;
use App\ConservationLegislation;
use App\RedList;
use App\Rules\UniqueTaxonName;
use App\Stage;
use App\Support\Localization;
use App\Taxon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreTaxon extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::check('create', [Taxon::class, $this->input('parent_id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['bail', 'required', new UniqueTaxonName($this->parent_id)],
            'parent_id' => ['nullable', 'exists:taxa,id'],
            'rank' => ['required', Rule::in(array_keys(Taxon::RANKS))],
            'author' => ['nullable', 'string'],
            'fe_old_id' => ['nullable', 'integer'],
            'fe_id' => ['nullable'],
            'restricted' => ['boolean'],
            'allochthonous' => ['boolean'],
            'invasive' => ['boolean'],
            'stages_ids' => ['nullable', 'array'],
            'stages_ids.*' => ['required', Rule::in(Stage::pluck('id'))],
            'conservation_legislations_ids' => [
                'nullable', 'array', Rule::in(ConservationLegislation::pluck('id')),
            ],
            'conservation_documents_ids' => [
                'nullable', 'array', Rule::in(ConservationDocument::pluck('id')),
            ],
            'red_lists_data' => ['nullable', 'array'],
            'red_lists_data.*' => ['array'],
            'red_lists_data.*.red_list_id' => [
                'required',
                Rule::in(RedList::pluck('id')),
            ],
            'red_lists_data.*.category' => [
                'required',
                Rule::in(RedList::CATEGORIES),
            ],
            'native_name' => ['required', 'array'],
            'description' => ['required', 'array'],
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
     * Create taxon using request data.
     *
     * @return \App\Taxon
     */
    protected function createTaxon()
    {
        return Taxon::create(array_merge(array_map('trim', $this->only(['name', 'rank'])), $this->only([
            'parent_id', 'fe_id', 'author', 'fe_old_id', 'restricted', 'allochthonous', 'invasive',
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
     * @return \App\Taxon
     */
    public function save()
    {
        return DB::transaction(function () {
            return tap($this->createTaxon(), function ($taxon) {
                $this->syncRelations($taxon);
                $this->logCreatedActivity($taxon);
            });
        });
    }

    /**
     * Log taxon created activity.
     *
     * @param  \App\Taxon  $taxon
     * @return void
     */
    protected function logCreatedActivity(Taxon $taxon)
    {
        activity()->performedOn($taxon)
            ->causedBy($this->user())
            ->log('created');
    }
}
