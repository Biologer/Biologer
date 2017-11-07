<?php

namespace App\Http\Controllers\Api;

use App\Taxon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class TaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $taxa = Taxon::with('parent')->filter($request)->orderBy('id');

        if ($request->input('all', false)) {
            return $taxa->get();
        }

        return $taxa->paginate($request->input('per_page', 15));
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Taxon  $taxon
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Taxon $taxon)
    {
        return $taxon;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:taxa,name',
            'parent_id' => 'nullable|exists:taxa,id',
            'category_level' => 'required|integer',
            'fe_old_id' => 'nullable',
            'fe_id' => 'nullable',
        ], [], [
            'parent_id' => 'parent',
        ]);

        return response()->json(Taxon::create($data), 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Taxon  $taxon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Taxon $taxon)
    {
        $data = request()->validate([
            'name' => [
                'required',
                Rule::unique('taxa', 'name')->ignore($taxon->id),
            ],
            'parent_id' => 'nullable|exists:taxa,id',
            'category_level' => [
                'required',
                'integer',
                'in:'.implode(',', array_keys(Taxon::getCategories())),
            ],
            'fe_old_id' => 'nullable',
            'fe_id' => 'nullable',
        ], [], [
            'parent_id' => 'parent',
        ]);

        $taxon->update($data);

        return $taxon;
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
