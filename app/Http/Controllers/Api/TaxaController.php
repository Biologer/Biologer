<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTaxon;
use App\Http\Requests\UpdateTaxon;
use App\Http\Resources\TaxonCollectionResource;
use App\Http\Resources\TaxonResource;
use App\Taxon;
use Illuminate\Http\Request;

class TaxaController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $relations = [
            'parent', 'stages', 'activity.causer', 'curators', 'ancestors.curators', 'synonyms',
        ];

        if ($request->boolean('withGroupsIds')) {
            $relations = array_merge($relations, [
                'groups' => function ($q) {
                    $q->select('id');
                },
                'ancestors.groups' => function ($q) {
                    $q->select('id');
                },
            ]);
        }

        $taxa = Taxon::with($relations)
            ->filter($request)
            ->orderBy('id')
            ->paginate($request->input('per_page', 15));

        return new TaxonCollectionResource($taxa);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Taxon  $taxon
     * @return \App\Http\Resources\TaxonResource
     */
    public function show(Taxon $taxon)
    {
        return new TaxonResource($taxon->load([
            'conservationLegislations', 'redLists', 'conservationDocuments',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaxon  $form
     * @return \App\Http\Resources\TaxonResource
     */
    public function store(StoreTaxon $form)
    {
        return new TaxonResource($form->save());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Taxon  $taxon
     * @param  \App\Http\Requests\UpdateTaxon  $form
     * @return \App\Http\Resources\TaxonResource
     */
    public function update(Taxon $taxon, UpdateTaxon $form)
    {
        return new TaxonResource($form->save($taxon));
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
}
