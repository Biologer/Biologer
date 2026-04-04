<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTransectSection;
use App\Http\Requests\UpdateTransectSection;
use App\Http\Resources\TransectSectionResource;
use App\TransectSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransectSectionsController
{
    /**
     * Get transect sections.
     *
     * @param  Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = TransectSection::with([
            'transectVisits', 'activity.causer',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TransectSectionResource::collection($result);
    }

    /**
     * Add new timed count observation.
     *
     * @param  StoreTransectSection $form
     * @return TransectSectionResource
     */
    public function store(StoreTransectSection $form)
    {
        return new TransectSectionResource($form->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  TransectSection $transectSection
     * @param  Request $request
     * @return TransectSectionResource
     */
    public function show(TransectSection $transectSection, Request $request)
    {
        abort_unless($transectSection->isCreatedBy($request->user()), 403);

        return new TransectSectionResource($transectSection);
    }

    /**
     * Update timed count observation.
     *
     * @param  TransectSection $transectSection
     * @param  UpdateTransectSection $form
     * @return TransectSectionResource
     */
    public function update(TransectSection $transectSection, UpdateTransectSection $form)
    {
        return new TransectSectionResource($form->save($transectSection));
    }

    /**
     * Delete timed count observation.
     *
     * @param  TransectSection $transectSection
     * @return JsonResponse
     */
    public function destroy(TransectSection $transectSection)
    {
        $transectSection->delete();

        return response()->json(null, 204);
    }
}
