<?php

namespace App\Http\Controllers\Api;

use App\Taxon;
use App\Http\Requests\StoreTaxon;
use App\Http\Requests\UpdateTaxon;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaxonResource;

class TaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $taxa = Taxon::with(['parent', 'stages', 'activity.causer'])->filter(request())->orderBy('id');

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
