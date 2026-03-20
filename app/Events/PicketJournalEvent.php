<?php
// app/Events/PicketJournalEvent.php

namespace App\Events;

use App\Models\PicketJournal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PicketJournalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $journal;
    public $action;

    /**
     * Create a new event instance.
     */
    public function __construct(PicketJournal $journal, $action = 'created')
    {
        $this->journal = $journal;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        // Broadcast ke channel khusus user
        return new PrivateChannel('user.' . $this->journal->user_id);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'picket-journal.' . $this->action;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'journal_id' => $this->journal->id,
            'user_id' => $this->journal->user_id,
            'user_name' => $this->journal->user->name,
            'date' => $this->journal->date->format('Y-m-d'),
            'activity' => $this->journal->activity,
            'status' => $this->journal->status,
            'action' => $this->action,
            'message' => $this->getMessage(),
            'time' => now()->toDateTimeString(),
        ];
    }

    private function getMessage()
    {
        $messages = [
            'created' => 'Jurnal piket baru telah dibuat',
            'updated' => 'Jurnal piket telah diperbarui',
            'status_changed' => 'Status jurnal piket berubah menjadi ' . $this->journal->status,
        ];

        return $messages[$this->action] ?? 'Jurnal piket telah diupdate';
    }
}