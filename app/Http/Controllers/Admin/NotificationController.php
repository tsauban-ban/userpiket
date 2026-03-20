<?php
// app/Http/Controllers/Admin/NotificationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    /**
     * Menampilkan semua notifikasi dari semua user (khusus admin)
     */
    public function index(Request $request)
    {
        // Statistik notifikasi
        $stats = [
            'total_notifications' => DB::table('notifications')->count(),
            'unread_notifications' => DB::table('notifications')->whereNull('read_at')->count(),
            'notifications_today' => DB::table('notifications')->whereDate('created_at', today())->count(),
            'users_with_notifications' => DB::table('notifications')->distinct('notifiable_id')->count('notifiable_id'),
        ];

        // Ambil semua notifikasi dengan filter
        $query = DB::table('notifications')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('notifiable_id', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter berdasarkan status read/unread
        if ($request->filled('status')) {
            if ($request->status == 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status == 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(20);
        
        // Ambil data user untuk dropdown filter
        $users = User::all();

        return view('admin.notification.index', compact('notifications', 'stats', 'users'));
    }

    /**
     * Mengirim notifikasi broadcast ke semua user
     */
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success',
        ]);

        $users = User::where('role', 'user')->get();
        
        $count = 0;
        foreach ($users as $user) {
            // Buat notifikasi manual
            $user->notify(new class($request->message, $request->type) extends Notification {
                public $message;
                public $type;
                
                public function __construct($message, $type)
                {
                    $this->message = $message;
                    $this->type = $type;
                }
                
                public function via($notifiable)
                {
                    return ['database'];
                }
                
                public function toDatabase($notifiable)
                {
                    return [
                        'message' => $this->message,
                        'type' => $this->type,
                        'action' => 'broadcast',
                        'time' => now()->toDateTimeString(),
                    ];
                }
            });
            $count++;
        }

        return redirect()->route('admin.notification.index')
            ->with('success', "Broadcast notifikasi terkirim ke {$count} user");
    }

    /**
     * Melihat detail notifikasi
     */
    public function show($id)
    {
        $notification = DB::table('notifications')->where('id', $id)->first();
        
        if (!$notification) {
            return redirect()->route('admin.notification.index')
                ->with('error', 'Notifikasi tidak ditemukan');
        }

        // Ambil data user
        $user = User::find($notification->notifiable_id);

        return view('admin.notification.show', compact('notification', 'user'));
    }

    /**
     * Menghapus notifikasi
     */
    public function destroy($id)
    {
        DB::table('notifications')->where('id', $id)->delete();

        return redirect()->route('admin.notification.index')
            ->with('success', 'Notifikasi dihapus');
    }

    /**
     * Menghapus semua notifikasi yang sudah dibaca
     */
    public function clearRead()
    {
        $deleted = DB::table('notifications')->whereNotNull('read_at')->delete();

        return redirect()->route('admin.notification.index')
            ->with('success', "{$deleted} notifikasi yang sudah dibaca telah dihapus");
    }
}