<?php

namespace App\Http\Controllers\Api;

use App\Announcement;
use App\Http\Requests\SaveAnnouncement;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\Request;

class AnnouncementsController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\AnnouncementResource
     */
    public function index(Request $request)
    {
        $query = Announcement::query();

        if ($request->has('page')) {
            return AnnouncementResource::collection(
                $query->paginate($request->input('per_page', 15))
            );
        }

        return AnnouncementResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SaveAnnouncement  $request
     * @return \App\Http\Resources\AnnouncementResource
     */
    public function store(SaveAnnouncement $request)
    {
        return new AnnouncementResource($request->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return new AnnouncementResource($announcement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SaveAnnouncement  $request
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(SaveAnnouncement $request, Announcement $announcement)
    {
        return new AnnouncementResource($request->update($announcement));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json(null, 204);
    }
}
