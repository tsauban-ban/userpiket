<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PicketSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PicketScheduleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = PicketSchedule::with('user.division');
            
            if ($request->filled('search')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }
            
            if ($request->filled('date')) {
                $query->where('date', $request->date);
            }
            
            if ($request->filled('day')) {
                $query->where('day', $request->day);
            }
            
            $schedules = $query->orderBy('date', 'desc')->orderBy('day')->get();
            $users = User::with('division')->get();
            
            return view('admin.picket-schedule.index', compact('schedules', 'users'));
        } catch (\Exception $e) {
            Log::error('Index error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('=== STORE METHOD START ===');
            Log::info('Request data:', $request->all());
            
            // Cek koneksi database
            try {
                DB::connection()->getPdo();
                Log::info('Database connected: ' . DB::connection()->getDatabaseName());
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi database gagal: ' . $e->getMessage()
                ], 500);
            }
            
            // Validasi
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Log::info('Validation passed, creating schedule...');
            
            // Create schedule
            $schedule = PicketSchedule::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'day' => $request->day,
                'location' => $request->location,
                'notes' => $request->notes
            ]);
            
            Log::info('Schedule created successfully:', $schedule->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Jadwal piket berhasil ditambahkan',
                'schedule' => $schedule->load('user')
            ]);
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            Log::error('Bindings: ', $e->getBindings());
            
            return response()->json([
                'success' => false,
                'message' => 'Error database: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('General error in store method: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $schedule = PicketSchedule::findOrFail($id);
            return response()->json($schedule);
        } catch (\Exception $e) {
            Log::error('Edit error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request for ID: ' . $id, $request->all());
            
            $schedule = PicketSchedule::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $schedule->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Jadwal piket berhasil diupdate',
                'schedule' => $schedule->load('user')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = PicketSchedule::findOrFail($id);
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal piket berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}