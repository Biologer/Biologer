<?php

namespace App\Http\Controllers\Api;

use App\Stage;
use App\Taxon;
use App\RedList;
use App\ConservationList;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaxonResource;

class TaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\TaxonResource
     */
    public function index()
    {
        $taxa = Taxon::with(['parent', 'stages'])->filter(request())->orderBy('id');

        if (request()->has('page')) {
            return TaxonResource::collection(
                $taxa->paginate(request('per_page', 15))
            );
        }

        return TaxonResource::collection($taxa->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Taxon  $taxon
     * @return \App\Http\Resources\TaxonResource
     */
    public function show(Taxon $taxon)
    {
        return new TaxonResource($taxon->load(['conservationLists', 'redLists']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Http\Resources\TaxonResource
     */
    public function store()
    {
        $this->authorize('create', [Taxon::class, request('parent_id')]);

        request()->validate([
            'name' => 'required|unique:taxa,name',
            'parent_id' => 'nullable|exists:taxa,id',
            'rank_level' => [
                'required',
                'integer',
                Rule::in(array_keys(Taxon::getRanks())),
            ],
            'fe_old_id' => 'nullable|integer',
            'fe_id' => 'nullable',
            'restricted' => 'boolean',
            'stages_ids' => 'nullable|array',
            'stages_ids.*' => [
                'required',
                Rule::in(Stage::pluck('id')->all()),
            ],
            'conservation_lists_ids' => 'nullable|array',
            'conservation_lists_ids.*' => [
                'required',
                Rule::in(ConservationList::pluck('id')->all()),
            ],
            'red_lists_data' => 'nullable|array',
            'red_lists_data.*' => 'array',
            'red_lists_data.*.red_list_id' => [
                'required',
                Rule::in(RedList::pluck('id')->all()),
            ],
            'red_lists_data.*.category' => [
                'required',
                Rule::in(RedList::CATEGORIES),
            ],
        ], [], [
            'parent_id' => 'parent',
        ]);

        $taxon = Taxon::create(request([
            'name', 'parent_id', 'rank_level', 'fe_old_id', 'fe_id', 'restricted',
        ]));

        $taxon->stages()->sync(request('stages_ids', []));
        $taxon->conservationLists()->sync(request('conservation_lists_ids', []));
        $taxon->redLists()->sync(
            $this->mapRedListsData(request('red_lists_data', []))
        );

        return new TaxonResource($taxon);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Taxon  $taxon
     * @return \App\Http\Resources\Taxon
     */
    public function update(Taxon $taxon)
    {
        request()->validate([
            'name' => [
                'required',
                Rule::unique('taxa', 'name')->ignore($taxon->id),
            ],
            'parent_id' => 'nullable|exists:taxa,id',
            'rank_level' => [
                'required',
                'integer',
                Rule::in(array_keys(Taxon::getRanks())),
            ],
            'fe_old_id' => 'nullable|integer',
            'fe_id' => 'nullable',
            'restricted' => 'boolean',
            'stages_ids' => 'nullable|array',
            'stages_ids.*' => [
                'required',
                Rule::in(Stage::pluck('id')->all()),
            ],
            'conservation_lists_ids' => 'nullable|array',
            'conservation_lists_ids.*' => [
                'required',
                Rule::in(ConservationList::pluck('id')->all()),
            ],
            'red_lists_data' => 'nullable|array',
            'red_lists_data.*' => 'array',
            'red_lists_data.*.red_list_id' => [
                'required',
                Rule::in(RedList::pluck('id')->all()),
            ],
            'red_lists_data.*.category' => [
                'required',
                Rule::in(RedList::CATEGORIES),
            ],
        ], [], [
            'parent_id' => 'parent',
        ]);

        $taxon->update(request([
            'name', 'parent_id', 'rank_level', 'fe_old_id', 'fe_id', 'restricted',
        ]));

        $taxon->stages()->sync(request('stages_ids', []));
        $taxon->conservationLists()->sync(request('conservation_lists_ids', []));
        $taxon->redLists()->sync(
            $this->mapRedListsData(request('red_lists_data', []))
        );

        return new TaxonResource($taxon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Taxon  $taxon
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Taxon $taxon)
    {
        $taxon->delete();

        return response()->json(null, 204);
    }

    /**
     * Map red list data to format required to store the value.
     * @param  array  $redListsData
     * @return array
     */
    protected function mapRedListsData($redListsData = [])
    {
        return collect($redListsData)->mapWithKeys(function ($item) {
            return [$item['red_list_id'] => ['category' => $item['category']]];
        })->all();
    }
}
