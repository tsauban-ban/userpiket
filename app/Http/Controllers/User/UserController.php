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

        $query = PicketJournal::where('user_id', $user->id);

        // FILTER STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // FILTER TANGGAL
        if ($request->date) {
            $query->whereDate('date', $request->date);
        }

        // FILTER ACTIVITY (jenis)
        if ($request->activity) {
            $query->where('activity', 'like', '%' . $request->activity . '%');
        }

        $schedules = $query->orderBy('date', 'desc')->get();

        // NOTIF
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $notificationCount = $user->unreadNotifications()->count();

        $user->load('division');

        return view('user.dashboard', compact(
            'user',
            'schedules',
            'notifications',
            'notificationCount'
        ));
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

    // ================== PICKET USER ==================

public function picketIndex()
{
    $data = PicketJournal::where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('user.picket.index', compact('data'));
}

public function picketShow($id)
{
    $picket = PicketJournal::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    return view('user.picket.show', compact('picket'));
}

public function startPicket($id)
{
    $picket = PicketJournal::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $picket->start_time = now();
    $picket->status = 'Pending'; // tetap pending
    $picket->save();

    return back()->with('success', 'Piket dimulai');
}

public function endPicket($id)
{
    $picket = PicketJournal::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $picket->update([
        'end_time' => now(),
        'status' => 'Done'
    ]);

    return back()->with('success', 'Piket selesai');
}

public function picketCreate()
{
    return view('user.picket.create');
}

public function picketStore(Request $request)
{
    PicketJournal::create([
        'user_id' => Auth::id(),
        'activity' => $request->activity,
        'date' => $request->date,
        'status' => 'Pending'
    ]);

     // VALIDASI
    $request->validate([
        'activity' => 'required',
        'date' => 'required|date',
        'before_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'after_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->all();

    // UPLOAD BEFORE PHOTO
    if ($request->hasFile('before_photo')) {
        $data['before_photo'] = $request->file('before_photo')->store('picket', 'public');
    }

    // UPLOAD AFTER PHOTO
    if ($request->hasFile('after_photo')) {
        $data['after_photo'] = $request->file('after_photo')->store('picket', 'public');
    }

    // TAMBAH USER ID
    $data['user_id'] = auth()->id();

    // SIMPAN KE DATABASE
    \App\Models\PicketJournal::create($data);

    return redirect()->route('picket.index')->with('success', 'Piket berhasil ditambahkan');

    return redirect()->route('user.picket.index')->with('success', 'Piket berhasil ditambahkan');
}
}