<?php

namespace App\Events;

use App\Announcement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAnnouncement
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Announcement
     */
    public $announcement;

    /**
     * Create a new event instance.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('announcements');
    }
}
