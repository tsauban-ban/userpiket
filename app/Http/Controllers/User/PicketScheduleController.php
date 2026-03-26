<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PicketSchedule;
use Illuminate\Http\Request;

class PicketScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = PicketSchedule::with('user.division');
        
        // Filter search by name
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        // Filter by day
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }
        
        $schedules = $query->orderBy('date', 'desc')
            ->orderBy('day')
            ->paginate(10);
        
        return view('user.picket-schedule.index', compact('schedules'));
    }
    
    public function show($id)
    {
        $schedule = PicketSchedule::with('user.division')->findOrFail($id);
        return view('user.picket-schedule.show', compact('schedule'));
    }
}