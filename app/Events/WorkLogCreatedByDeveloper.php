<?php

namespace App\Events;

use App\Models\Developer;
use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkLogCreatedByDeveloper implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    /**
     * Create a new event instance.
     */
    public function __construct($developer, $project)
    {
        $developer = Developer::where('id', $developer)->first(['first_name', 'last_name']);
        $project = Project::where('id', $project)->first(['name']);
        $this->message = $developer->full_name . ' created work log for ' . $project->name . ' project';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return  array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('timesheet'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new-worklog';
    }
}
