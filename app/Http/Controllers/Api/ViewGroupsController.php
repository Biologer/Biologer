<?php

namespace App\Http\Controllers\Api;

use App\Taxon;
use App\ViewGroup;
use App\Support\Localization;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\ViewGroupResource;

class ViewGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if (request()->has('page')) {
            return ViewGroupResource::collection(
                ViewGroup::with('groups')->paginate(request('per_page', 15))
            );
        }

        return ViewGroupResource::collection(ViewGroup::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        request()->validate([
            'parent_id' => [
                'nullable',
                Rule::exists('view_groups', 'id')->whereNull('parent_id'),
            ],
            'name' => ['required', 'array', 'min:1'],
            'description' => ['required', 'array'],
            'taxa_ids' => [
                'array',
                Rule::in(Taxon::pluck('id')->all())
            ],
        ]);

        $group = ViewGroup::create($this->getData());
        $group->taxa()->sync(request('taxa_ids', []));

        return new ViewGroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ViewGroup  $group
     * @return \App\Http\Resources\ViewGroupResource
     */
    public function show(ViewGroup $group)
    {
        return new ViewGroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\ViewGroup  $group
     * @return \App\Http\Resources\ViewGroupResource
     */
    public function update(ViewGroup $group)
    {
        request()->validate([
            'parent_id' => [
                'nullable',
                Rule::exists('view_groups', 'id')->whereNull('parent_id'),
            ],
            'name' => ['required', 'array'],
            'description' => ['required', 'array'],
            'taxa_ids' => [
                'array',
                Rule::in(Taxon::pluck('id')->all())
            ],
        ]);

        $group->update($this->getData());
        $group->taxa()->sync(request('taxa_ids', []));

        return new ViewGroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ViewGroup  $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ViewGroup $group)
    {
        $group->delete();

        return response()->json(null, 204);
    }

    /**
     * Get data from request.
     *
     * @return array
     */
    protected function getData()
    {
        return array_merge(request()->only([
            'parent_id',
        ]), Localization::transformTranslations(request()->only([
            'name', 'description',
        ])));
    }
}
