<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ViewGroups\ViewGroupsExport;
use App\ViewGroup;

class ViewGroupsController
{
    /**
     * List view groups to admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.view-groups.index', [
            'exportColumns' => ViewGroupsExport::availableColumnData(),
        ]);
    }

    /**
     * Show page to create view group.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.view-groups.create', [
            'rootGroups' => ViewGroup::roots()->get(),
        ]);
    }

    /**
     * Show page to edit view group.
     *
     * @param  \App\ViewGroup  $group
     * @return \Illuminate\View\View
     */
    public function edit(ViewGroup $group)
    {
        return view('admin.view-groups.edit', [
            'group' => $group->load('taxa'),
            'rootGroups' => ViewGroup::roots()->where('id', '!=', $group->id)->get(),
        ]);
    }
}
