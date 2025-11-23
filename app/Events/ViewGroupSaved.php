<?php

namespace App\Events;

use App\Models\ViewGroup;
use Illuminate\Queue\SerializesModels;

class ViewGroupSaved
{
    use SerializesModels;

    /**
     * @var \App\Models\ViewGroup
     */
    public $viewGroup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ViewGroup $viewGroup)
    {
        $this->viewGroup = $viewGroup;
    }
}
