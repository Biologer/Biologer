<?php

namespace App\Events;

use App\ViewGroup;
use Illuminate\Queue\SerializesModels;

class ViewGroupDeleted
{
    use SerializesModels;

    /**
     * @var \App\ViewGroup
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
