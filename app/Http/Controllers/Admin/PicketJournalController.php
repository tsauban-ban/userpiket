<?php

namespace App\Http\Controllers\Admin;

use App\Events\PicketJournalEvent;
use App\Http\Controllers\Controller;
use App\Models\PicketJournal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PicketJournalController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'user')->get();

        // Generate daftar minggu untuk 2 bulan terakhir
        $weeks = $this->generateWeeksList();
        
        $query = PicketJournal::with('user.division');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('activity', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        // Filter berdasarkan minggu
        if ($request->filled('week')) {
            [$startDate, $endDate] = explode('|', $request->week);
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        // Filter berdasarkan hari
        if ($request->filled('day')) {
            $query->whereRaw('DAYNAME(date) = ?', [$request->day]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal spesifik
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
        $startDate = Carbon::now()->subMonths(2)->startOfMonth(); // 2 bulan lalu
        
        // Loop dari 2 bulan lalu sampai sekarang
        while ($startDate <= $now) {
            $weekStart = $startDate->copy()->startOfWeek(); // Mulai Senin
            $weekEnd = $weekStart->copy()->endOfWeek(); // Akhir Minggu
            
            // Format untuk ditampilkan
            $label = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y');
            $value = $weekStart->format('Y-m-d') . '|' . $weekEnd->format('Y-m-d');
            
            $weeks[$value] = $label;
            
            // Maju ke minggu berikutnya
            $startDate->addWeek();
        }
        
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
        ]);

        $data = $request->all();
        
        // Format waktu
        if ($request->start_time) {
            $data['start_time'] = Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
        }
        if ($request->end_time) {
            $data['end_time'] = Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');
        }

        $journal = PicketJournal::create($data);

        // TRIGGER EVENT: Jurnal baru dibuat
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
        
        // Handle waktu
        if ($request->start_time) {
            $data['start_time'] = Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
        }
        if ($request->end_time) {
            $data['end_time'] = Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');
        }

        // Handle upload foto
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

        // TRIGGER EVENT: Cek apakah status berubah
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
        // Hapus foto-foto
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
}