<?php

namespace App\Http\Controllers\Api;

use App\Taxon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        list($sortField, $sortOrder) = explode('.', request('sort_by', 'name.asc'));

        $taxa = Taxon::orderBy($sortField, $sortOrder)->orderBy('id');

        if (request()->has('name')) {
            $taxa = $taxa->where('name', 'like', request('name', '').'%');
        }

        if (request('all')) {
            return $taxa->get();
        }

        return $taxa->paginate(
            request('per_page', 15)
        );
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function show($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Taxon::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
