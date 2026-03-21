<?php

namespace App\Http\Controllers\Admin;

use App\Events\PicketJournalEvent;
use App\Http\Controllers\Controller;
use App\Models\PicketJournal;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PicketJournalController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'user')->get();

        
        
        $weeks = $this->generateWeeksList();
        
        $query = PicketJournal::with('user.division');

        
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('activity', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        
        
        if ($request->filled('week')) {
            [$startDate, $endDate] = explode('|', $request->week);
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        
        
        if ($request->filled('day')) {
            $query->whereRaw('DAYNAME(date) = ?', [$request->day]);
        }

        
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        
        
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $journals = $query->latest()->paginate(15)->withQueryString();

        return view('admin.picketjournal.index', compact('journals', 'users', 'weeks'));
    }

    /**
     * Generate daftar minggu untuk 2 bulan terakhir
     */
    private function generateWeeksList()
{
    $weeks = [];
    $now = Carbon::now();
    
    
    
    $startDate = Carbon::now()->subMonths(2)->startOfMonth();
    
    
    
    $endDate = Carbon::now()->addMonths(2)->endOfMonth();
    
    
    
    while ($startDate <= $endDate) {
        $weekStart = $startDate->copy()->startOfWeek(); 
        
        $weekEnd = $weekStart->copy()->endOfWeek(); 
        
        
        
        
        $label = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y');
        $value = $weekStart->format('Y-m-d') . '|' . $weekEnd->format('Y-m-d');
        
        $weeks[$value] = $label;
        
        
        
        $startDate->addWeek();
    }
    
    
    
    $weeks = array_unique($weeks);
    
    
    
    ksort($weeks);
    
    return $weeks;
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.picketjournal.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'date' => 'required|date',
        'activity' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'nullable|string',
        'status' => 'required|in:Hadir,Sakit,Izin,Alpha,Terlambat,pending,done',
        'start_time' => 'nullable|date_format:H:i',
        'end_time' => 'nullable|date_format:H:i',
        'before_photo' => 'nullable|image|max:5120',
        'after_photo' => 'nullable|image|max:5120',
        'notes' => 'nullable|string',
    ]);

    $data = $request->all();
    
    
    
    if ($request->start_time) {
        $data['start_time'] = Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
    }
    if ($request->end_time) {
        $data['end_time'] = Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');
    }

    
    
    if ($request->hasFile('before_photo')) {
        $data['before_photo'] = $request->file('before_photo')->store('picket-photos', 'public');
    }
    if ($request->hasFile('after_photo')) {
        $data['after_photo'] = $request->file('after_photo')->store('picket-photos', 'public');
    }

    $journal = PicketJournal::create($data);

    
    
    event(new PicketJournalEvent($journal, 'created'));

    return redirect()->route('admin.picketjournal.index')
        ->with('success', 'Jurnal piket berhasil ditambahkan!');
}
    /**
     * Display the specified resource.
     */
    public function show(PicketJournal $picketjournal)
    {
        $picketjournal->load('user.division');
        return view('admin.picketjournal.show', ['journal' => $picketjournal]);
    }

    /**
     * Show the form for editing the specified resource.
     * METHOD INI YANG HILANG!
     */
    public function edit(PicketJournal $picketjournal)
    {
        $picketjournal->load('user.division');
        $users = User::where('role', 'user')->get();
        return view('admin.picketjournal.edit', [
            'journal' => $picketjournal,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, PicketJournal $picketjournal)
    {
        $oldStatus = $picketjournal->status;
        
        $request->validate([
            'activity' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'status' => 'required|in:Hadir,Sakit,Izin,Alpha,Terlambat,pending,done,approved,rejected',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'before_photo' => 'nullable|image|max:5120',
            'after_photo' => 'nullable|image|max:5120',
        ]);

        $data = [
            'activity' => $request->activity,
            'description' => $request->description,
            'location' => $request->location,
            'status' => $request->status,
        ];
        
        
        
        if ($request->start_time) {
            $data['start_time'] = Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
        }
        if ($request->end_time) {
            $data['end_time'] = Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');
        }

        
        
        if ($request->hasFile('before_photo')) {
            if ($picketjournal->before_photo) {
                Storage::disk('public')->delete($picketjournal->before_photo);
            }
            $data['before_photo'] = $request->file('before_photo')->store('picket-photos', 'public');
        }

        if ($request->hasFile('after_photo')) {
            if ($picketjournal->after_photo) {
                Storage::disk('public')->delete($picketjournal->after_photo);
            }
            $data['after_photo'] = $request->file('after_photo')->store('picket-photos', 'public');
        }

        $picketjournal->update($data);

        
        
        if ($oldStatus != $request->status) {
            event(new PicketJournalEvent($picketjournal->fresh(), 'status_changed'));
        } else {
            event(new PicketJournalEvent($picketjournal->fresh(), 'updated'));
        }

        return redirect()->route('admin.picketjournal.show', $picketjournal->id)
            ->with('success', 'Jurnal berhasil diperbarui!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PicketJournal $picketjournal)
    {
        
    
        if ($picketjournal->before_photo) {
            Storage::disk('public')->delete($picketjournal->before_photo);
        }
        if ($picketjournal->after_photo) {
            Storage::disk('public')->delete($picketjournal->after_photo);
        }

        $picketjournal->delete();

        return redirect()->route('admin.picketjournal.index')
            ->with('success', 'Jurnal berhasil dihapus!');
    }

     public function exportPdf(Request $request)
    {
        
    
        $query = PicketJournal::with('user.division');

        
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('activity', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        
        
        if ($request->filled('week')) {
            [$startDate, $endDate] = explode('|', $request->week);
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        
        
        if ($request->filled('day')) {
            $query->whereRaw('DAYNAME(date) = ?', [$request->day]);
        }

        
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $journals = $query->latest()->get();

        
        
        $data = [
            'journals' => $journals,
            'export_date' => Carbon::now()->locale('id')->isoFormat('D MMMM Y HH:mm'),
            'total' => $journals->count(),
            'filters' => [
                'search' => $request->search,
                'week' => $request->week,
                'day' => $request->day,
                'status' => $request->status,
            ]
        ];

        
        
        $pdf = Pdf::loadView('admin.picketjournal.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        
        
        return $pdf->download('laporan-piket-' . date('Y-m-d') . '.pdf');
    }
}