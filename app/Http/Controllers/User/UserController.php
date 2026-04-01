<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PicketJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // NOTIFIKASI
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $notificationCount = $user->unreadNotifications()->count();

        // Load relasi division
        $user->load('division');

        return view('user.dashboard', compact(
            'user',
            'notifications',
            'notificationCount'
        ));
    }

    // Method lainnya tetap sama...
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

    // ================== PICKET USER (JOURNAL) ==================

    public function picketIndex()
    {
        $data = PicketJournal::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.picket.index', compact('data'));
    }

    public function picketShow($id)
    {
        $picket = PicketJournal::findOrFail($id);
        return view('user.picket.show', compact('picket'));
    }

    public function startPicket($id)
    {
        $picket = PicketJournal::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $picket->update([
            'start_time' => now()->format('H:i:s'),
            'status' => 'OnGoing'
        ]);

        return redirect()->route('user.picket.show', $id);
    }

    // Contoh di UserController atau PicketController
    // ... (method lainnya)

    public function endPicket(Request $request, $id)
    {
        $picket = PicketJournal::findOrFail($id);

        if ($request->hasFile('before_photo')) {
            // Simpan ke storage/app/public/picket_photos
            $picket->before_photo = $request->file('before_photo')->store('picket_photos', 'public');
        }

        if ($request->hasFile('after_photo')) {
            $picket->after_photo = $request->file('after_photo')->store('picket_photos', 'public');
        }

        $picket->status = 'Done';
        $picket->end_time = now()->format('H:i:s');
        $picket->save();

        return redirect()->back();
    }

    public function picketStore(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'date' => 'required|date',
            'before_photo' => 'nullable|image|max:2048',
            'after_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Samakan menggunakan store() agar masuk ke folder storage
        if ($request->hasFile('before_photo')) {
            $data['before_photo'] = $request->file('before_photo')->store('picket_photos', 'public');
        }

        if ($request->hasFile('after_photo')) {
            $data['after_photo'] = $request->file('after_photo')->store('picket_photos', 'public');
        }

        $data['user_id'] = auth()->id();
        $data['status'] = 'Pending';

        PicketJournal::create($data);

        return redirect()->route('user.picket.index')->with('success', 'Piket berhasil ditambahkan');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $picket = PicketJournal::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($request->hasFile('before_photo')) {
            $picket->before_photo = $request->file('before_photo')->store('picket_photos', 'public');
        }

        if ($request->hasFile('after_photo')) {
            $picket->after_photo = $request->file('after_photo')->store('picket_photos', 'public');
        }

        $picket->save();
        return back();
    }    

}   