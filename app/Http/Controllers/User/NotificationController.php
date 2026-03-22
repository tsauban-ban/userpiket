<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menampilkan halaman semua notifikasi (khusus user)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua notifikasi dengan pagination
        $notifications = $user->notifications()
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);
        
        // Statistik
        $totalNotifications = $user->notifications()->count();
        $unreadCount = $user->unreadNotifications()->count();
        $readCount = $totalNotifications - $unreadCount;
        
        return view('user.notifications', compact('notifications', 'totalNotifications', 'unreadCount', 'readCount'));
    }
    
    /**
     * Tandai notifikasi sudah dibaca (redirect)
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
        }
        
        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan');
    }
    
    /**
     * Tandai semua notifikasi sudah dibaca (redirect)
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }
    
    /**
     * Hapus semua notifikasi yang sudah dibaca (redirect)
     */
    public function deleteRead()
    {
        $user = Auth::user();
        $user->notifications()->whereNotNull('read_at')->delete();
        
        return redirect()->back()->with('success', 'Notifikasi yang sudah dibaca dihapus');
    }
}