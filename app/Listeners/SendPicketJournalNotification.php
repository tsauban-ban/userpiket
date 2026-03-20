<?php
// app/Listeners/SendPicketJournalNotification.php

namespace App\Listeners;

use App\Events\PicketJournalEvent;
use App\Notifications\PicketJournalNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPicketJournalNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PicketJournalEvent $event): void
    {
        $journal = $event->journal;
        $action = $event->action;

        // 1. Notifikasi ke user yang bersangkutan
        if ($journal->user) {
            $journal->user->notify(new PicketJournalNotification($journal, $action));
        }

        // 2. Notifikasi ke admin (jika perlu)
        if (in_array($action, ['created', 'status_changed'])) {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new PicketJournalNotification($journal, $action));
            }
        }

        // 3. Notifikasi ke user dalam divisi yang sama (jika perlu)
        if ($action == 'created' && $journal->user->division) {
            $divisionUsers = User::where('division_id', $journal->user->division_id)
                                  ->where('id', '!=', $journal->user_id)
                                  ->get();
            foreach ($divisionUsers as $user) {
                $user->notify(new PicketJournalNotification($journal, 'related_created'));
            }
        }
    }
}