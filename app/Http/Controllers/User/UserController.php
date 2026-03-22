<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PicketJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil jadwal
        $schedules = PicketJournal::where('user_id', $user->id)
                                  ->orderBy('date', 'desc')
                                  ->get();
        
        // ========== AMBIL NOTIFIKASI ==========
        // Ambil notifikasi untuk dropdown
        $notifications = $user->notifications()
                              ->orderBy('created_at', 'desc')
                              ->take(10)
                              ->get();
        
        // Hitung jumlah notifikasi yang belum dibaca
        $notificationCount = $user->unreadNotifications()->count();
        // =====================================

         // Pastikan data divisi di-load
        $user->load('division');
        
        // Kirim semua variabel ke view
        return view('user.dashboard', compact('user', 'schedules', 'notifications', 'notificationCount'));
    }

    public function filterByDay(Request $request)
    {
        $day = $request->day;
        
        $schedules = PicketJournal::where('user_id', Auth::id())
                                  ->where('day', $day)
                                  ->orderBy('date')
                                  ->get();
        
        if ($request->ajax()) {
            $html = view('user.partials.schedule-table', compact('schedules'))->render();
            return response()->json(['html' => $html]);
        }
        
        return redirect()->back();
    }

    public function updateStatus(Request $request, $id)
    {
        $schedule = PicketJournal::where('user_id', Auth::id())->findOrFail($id);
        $schedule->status = $request->status;
        $schedule->save();

        return response()->json(['success' => true]);
    }
}