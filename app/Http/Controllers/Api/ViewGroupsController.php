<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaveViewGroup;
use App\Http\Resources\ViewGroupResource;
use App\Models\ViewGroup;

class ViewGroupsController
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
     *
     * @param  \App\Http\Requests\SaveViewGroup  $request
     * @return \App\Http\Resources\ViewGroupResource
     */
    public function store(SaveViewGroup $request)
    {
        return new ViewGroupResource($request->save(new ViewGroup));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ViewGroup  $group
     * @return \App\Http\Resources\ViewGroupResource
     */
    public function show(ViewGroup $group)
    {
        return new ViewGroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ViewGroup  $group
     * @param  \App\Http\Requests\SaveViewGroup  $request
     * @return \App\Http\Resources\ViewGroupResource
     */
    public function update(ViewGroup $group, SaveViewGroup $request)
    {
        return new ViewGroupResource($request->save($group));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ViewGroup  $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ViewGroup $group)
    {
        $group->delete();

        return response()->json(null, 204);
    }
}
