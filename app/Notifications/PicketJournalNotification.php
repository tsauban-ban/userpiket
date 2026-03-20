<?php
// app/Notifications/PicketJournalNotification.php

namespace App\Notifications;

use App\Models\PicketJournal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class PicketJournalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $journal;
    protected $action; // 'created', 'updated', 'status_changed'

    /**
     * Create a new notification instance.
     */
    public function __construct(PicketJournal $journal, $action = 'created')
    {
        $this->journal = $journal;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Bisa ditambah 'broadcast' untuk real-time
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $messages = [
            'created' => 'Jurnal piket baru telah dibuat',
            'updated' => 'Jurnal piket telah diperbarui',
            'status_changed' => 'Status jurnal piket berubah menjadi ' . $this->journal->status,
        ];

        $message = $messages[$this->action] ?? 'Jurnal piket telah diupdate';

        return [
            'journal_id' => $this->journal->id,
            'user_id' => $this->journal->user_id,
            'user_name' => $this->journal->user->name,
            'date' => $this->journal->date->format('Y-m-d'),
            'activity' => $this->journal->activity,
            'status' => $this->journal->status,
            'action' => $this->action,
            'message' => $message,
            'time' => Carbon::now()->toDateTimeString(),
            'link' => route('admin.picketjournal.show', $this->journal->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $statusLabels = [
            'pending' => 'Pending',
            'done' => 'Selesai',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'Hadir' => 'Hadir',
            'Sakit' => 'Sakit',
            'Izin' => 'Izin',
            'Alpha' => 'Alpha',
            'Terlambat' => 'Terlambat',
        ];

        $statusLabel = $statusLabels[$this->journal->status] ?? $this->journal->status;

        return (new MailMessage)
                    ->subject('Notifikasi Jurnal Piket')
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Ada update pada jurnal piket:')
                    ->line('**User:** ' . $this->journal->user->name)
                    ->line('**Tanggal:** ' . $this->journal->date->format('d/m/Y'))
                    ->line('**Activity:** ' . $this->journal->activity)
                    ->line('**Status:** ' . $statusLabel)
                    ->action('Lihat Detail', route('admin.picketjournal.show', $this->journal->id))
                    ->line('Terima kasih telah menggunakan aplikasi piket!');
    }

    /**
     * Get the array representation for broadcasting.
     */
    public function toBroadcast($notifiable)
    {
        return [
            'data' => $this->toDatabase($notifiable),
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType()
    {
        return 'picket-journal';
    }
}