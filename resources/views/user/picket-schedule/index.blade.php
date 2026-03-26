@extends('layouts.app')

@section('title', 'Picket Schedule')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Picket Schedule</h1>
                <p class="text-gray-600 mt-1">Lihat jadwal piket semua user</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                    {{ isset($schedules) ? $schedules->total() : 0 }} jadwal
                </span>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="material-symbols-outlined text-blue-500 mr-2">info</span>
            <p class="text-blue-700">Halaman ini hanya untuk melihat jadwal piket. Anda tidak dapat menambah, mengedit, atau menghapus jadwal.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Jadwal</h2>
        
        <form method="GET" action="{{ route('user.picket-schedule.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Cari Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama user..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Filter Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" 
                           name="date" 
                           value="{{ request('date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Filter Hari -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                    <select name="day" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">SEMUA HARI</option>
                        <option value="Monday" {{ request('day') == 'Monday' ? 'selected' : '' }}>Senin</option>
                        <option value="Tuesday" {{ request('day') == 'Tuesday' ? 'selected' : '' }}>Selasa</option>
                        <option value="Wednesday" {{ request('day') == 'Wednesday' ? 'selected' : '' }}>Rabu</option>
                        <option value="Thursday" {{ request('day') == 'Thursday' ? 'selected' : '' }}>Kamis</option>
                        <option value="Friday" {{ request('day') == 'Friday' ? 'selected' : '' }}>Jumat</option>
                        <option value="Saturday" {{ request('day') == 'Saturday' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Sunday" {{ request('day') == 'Sunday' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>

                <!-- Aksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aksi</label>
                    <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                        <span class="material-symbols-outlined text-base align-middle mr-1">search</span>
                        Cari
                    </button>
                </div>
            </div>

            <!-- Reset Filter -->
            @if(request()->anyFilled(['search', 'date', 'day']))
            <div class="mt-2 text-right">
                <a href="{{ route('user.picket-schedule.index') }}" class="text-sm text-orange-500 hover:text-orange-600 flex items-center gap-1 justify-end">
                    <span class="material-symbols-outlined text-sm">close</span>
                    Reset semua filter
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Tabel Jadwal Piket -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama User</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Hari</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Lokasi</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Catatan</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $index => $schedule)
                    <tr class="hover:bg-orange-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $schedules->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $schedule->user->name }}</div>
                            @if($schedule->user->division)
                                <div class="text-xs text-gray-500">{{ $schedule->user->division->division_name ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @switch($schedule->day)
                                @case('Monday') <span class="text-gray-700">Senin</span> @break
                                @case('Tuesday') <span class="text-gray-700">Selasa</span> @break
                                @case('Wednesday') <span class="text-gray-700">Rabu</span> @break
                                @case('Thursday') <span class="text-gray-700">Kamis</span> @break
                                @case('Friday') <span class="text-gray-700">Jumat</span> @break
                                @case('Saturday') <span class="text-gray-700">Sabtu</span> @break
                                @case('Sunday') <span class="text-gray-700">Minggu</span> @break
                                @default {{ $schedule->day }}
                            @endswitch
                        </td>
                        <td class="px-4 py-3">
                            @if($schedule->location)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $schedule->location }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($schedule->notes)
                                <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $schedule->notes }}">
                                    {{ Str::limit($schedule->notes, 50) }}
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('user.picket-schedule.show', $schedule->id) }}" 
                               class="inline-flex items-center gap-1 text-orange-500 hover:text-orange-700 transition" 
                               title="Lihat Detail">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                                <span class="text-sm">Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                            <span class="material-symbols-outlined text-6xl mb-3 text-gray-300">schedule</span>
                            <p class="text-lg font-medium">No Data</p>
                            <p class="text-sm mt-1">Belum ada jadwal piket</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($schedules) && method_exists($schedules, 'links') && $schedules->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $schedules->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection