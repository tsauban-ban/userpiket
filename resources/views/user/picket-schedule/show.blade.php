@extends('layouts.app')

@section('title', 'Detail Jadwal Piket')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Jadwal Piket</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap jadwal piket</p>
            </div>
            <a href="{{ route('user.picket-schedule.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center gap-2 transition">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Informasi Jadwal</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div class="border-b pb-3">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama User</label>
                        <p class="text-gray-800 font-medium">{{ $schedule->user->name }}</p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Divisi</label>
                        <p class="text-gray-800">{{ $schedule->user->division->division_name ?? '-' }}</p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-800">{{ $schedule->user->email ?? '-' }}</p>
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div class="border-b pb-3">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal</label>
                        <p class="text-gray-800">{{ \Carbon\Carbon::parse($schedule->date)->format('d F Y') }}</p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Hari</label>
                        <p class="text-gray-800">
                            @switch($schedule->day)
                                @case('Monday') Senin @break
                                @case('Tuesday') Selasa @break
                                @case('Wednesday') Rabu @break
                                @case('Thursday') Kamis @break
                                @case('Friday') Jumat @break
                                @case('Saturday') Sabtu @break
                                @case('Sunday') Minggu @break
                                @default {{ $schedule->day }}
                            @endswitch
                        </p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Lokasi</label>
                        <p class="text-gray-800">{{ $schedule->location ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Catatan -->
            <div class="mt-6 pt-4 border-t">
                <label class="block text-sm font-medium text-gray-500 mb-2">Catatan</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700">{{ $schedule->notes ?? 'Tidak ada catatan' }}</p>
                </div>
            </div>
            
            <!-- Info Footer -->
            <div class="mt-6 pt-4 border-t text-center">
                <p class="text-xs text-gray-400">
                    Dibuat pada: {{ $schedule->created_at->format('d/m/Y H:i') }}<br>
                    Terakhir diperbarui: {{ $schedule->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection