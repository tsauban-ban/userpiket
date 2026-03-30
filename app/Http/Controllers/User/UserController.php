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

    public function endPicket(Request $request, $id)
    {
        $picket = PicketJournal::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        // upload before
        if ($request->hasFile('before_photo')) {
            $file = $request->file('before_photo');
            $nama = time().'_before.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $nama);
            $picket->before_photo = $nama;
        }
    
        // upload after
        if ($request->hasFile('after_photo')) {
            $file = $request->file('after_photo');
            $nama = time().'_after.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $nama);
            $picket->after_photo = $nama;
        }
    
        $picket->update([
            'end_time' => now()->format('H:i:s'),
            'status' => 'Done'
        ]);
    
        return redirect()->route('user.picket.show', $id);
    }


    public function picketCreate()
    {
        return view('user.picket.create');
    }

    public function picketStore(Request $request)
    {
        // VALIDASI
        $request->validate([
            'activity' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'before_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'after_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        // BEFORE
        if ($request->hasFile('before_photo')) {
            $file = $request->file('before_photo'); // ✅ FIX
            $nama = time().'_before.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $nama);

            $data['before_photo'] = $nama; // ✅ WAJIB
        }

        // AFTER
        if ($request->hasFile('after_photo')) {
            $file = $request->file('after_photo'); // ✅ FIX
            $nama = time().'_after.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $nama);

            $data['after_photo'] = $nama; // ✅ WAJIB
        }

        // TAMBAH USER ID
        $data['user_id'] = auth()->id();
        $data['status'] = 'Pending';

        // SIMPAN
        PicketJournal::create($data);

        return redirect()->route('user.picket.index')
            ->with('success', 'Piket berhasil ditambahkan');
    }

    public function uploadPhoto(Request $request, $id)
    {
        // $picket = PicketJournal::findOrFail($id);
        $picket = PicketJournal::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        if ($request->hasFile('before_photo')) {
            $file = $request->file('before_photo');
            $nama = time().'_before.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $nama);
            $picket->before_photo = $nama;
        }

        if ($request->hasFile('after_photo')) {
            $file = $request->file('after_photo');
            $nama = time().'_after.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $nama);
            $picket->after_photo = $nama;
        }

        $picket->save();

        return back();
    }
}   