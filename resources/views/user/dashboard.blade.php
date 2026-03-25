@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="container mx-auto p-6">
    <!-- INFORMASI AKUN (DASHBOARD) -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-teal-600">person</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>
            <p class="text-gray-600 mt-1">{{ $user->name ?? 'User' }}</p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Akun Anda</h3>
            <table class="w-full">
                <tr class="border-b">
                    <td class="py-2 text-gray-600 w-1/3">Nama Lengkap</td>
                    <td class="py-2 font-medium">: {{ $user->name ?? '-' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-gray-600">Email</td>
                    <td class="py-2 font-medium">: {{ $user->email ?? '-' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-gray-600">Divisi</td>
                    <td class="py-2 font-medium">: {{ $user->division->division_name ?? 'Management Account' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-gray-600">Role</td>
                    <td class="py-2 font-medium">: {{ ucfirst($user->role ?? 'User') }}</td>
                </tr>
            </table>
        </div>
        
        <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
            <p class="text-yellow-800 text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-base">info</span>
                Anda login sebagai user biasa. Halaman admin tidak dapat diakses.
            </p>
        </div>
    </div>

    <!-- HEADER DIVISI (BERUBAH SESUAI DIVISI USER) -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <!-- JUDUL DIVISI BERUBAH SESUAI DIVISI USER -->
                <h1 class="text-3xl font-bold text-gray-800">
                    DIVISI {{ strtoupper($user->division->division_name ?? 'MA') }}
                </h1>
                
                <!-- Icon Lonceng Notifikasi -->
                <div class="relative">
                    <button id="bellButton" type="button" class="relative p-2 hover:bg-gray-100 rounded-full transition focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($notificationCount) && $notificationCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Dropdown Notifikasi -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-[500px] bg-white rounded-lg shadow-xl border z-50">
                        <div class="p-3 border-b bg-gray-50 flex justify-between items-center">
                            <h3 class="font-semibold">Notifikasi</h3>
                            <span class="text-xs text-gray-500">{{ $notificationCount ?? 0 }} belum dibaca</span>
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto">
                            @if(isset($notifications) && $notifications->count() > 0)
                                @foreach($notifications as $index => $notification)
                                <div class="p-3 border-b hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-yellow-50' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-2">
                                                <span class="text-xs text-gray-400 font-medium">{{ $index + 1 }}.</span>
                                                <p class="text-sm text-gray-800 flex-1">
                                                    {{ $notification->data['message'] ?? 'Tidak ada pesan' }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3 mt-1 ml-5">
                                                @if(isset($notification->data['sender']))
                                                    <span class="text-xs text-gray-400">Dari: {{ $notification->data['sender'] }}</span>
                                                @endif
                                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @if(is_null($notification->read_at))
                                            <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-orange-500 hover:text-orange-700" title="Tandai sudah dibaca">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-green-600">✓ Dibaca</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="p-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <p>Belum ada notifikasi</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-2 border-t text-center">
                            <a href="{{ route('user.notifications.index') }}" class="text-xs text-orange-500 hover:text-orange-600">
                                Lihat semua notifikasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ganti User dan Logout -->
            <div class="flex items-center gap-3">
                <button type="button" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-gray-700">Ganti User</span>
                </button>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Nama User dan Divisi -->
        <div class="mt-4">
            <p class="text-gray-800 font-medium">{{ $user->name ?? 'User' }}</p>
            <p class="text-gray-500 text-sm">Divisi {{ $user->division->division_name ?? 'Management Account' }}</p>
        </div>
    </div>

    <!-- Navigation Hari dan Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Jadwal Piket Anda</h2>
            <span class="text-sm text-gray-500">Total: {{ isset($schedules) ? $schedules->count() : 0 }} jadwal</span>
        </div>
        
        <!-- Navigation Hari -->
        <div class="flex gap-3 mb-6">
            @php $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT']; @endphp
            @foreach($days as $day)
                <button type="button" class="day-filter px-6 py-2 rounded-lg font-medium transition bg-gray-100 text-gray-700 hover:bg-orange-500 hover:text-white" data-day="{{ $day }}">
                    {{ $day }}
                </button>
            @endforeach
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- JENIS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                    <select name="activity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua</option>
                        <option value="Keamanan">Keamanan</option>
                        <option value="Kebersihan">Kebersihan</option>
                    </select>
                </div>

                <!-- STATUS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua</option>
                        <option value="Pending">Pending</option>
                        <option value="Done">Done</option>
                        <option value="Alpha">Alpha</option>
                    </select>
                </div>

            
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Aksi</label>
                <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                    Cari
                </button>
            </div>
        </form>
        
        <!-- Tabel Jadwal Piket -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Hari</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Divisi</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Jenis Piket</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Keterangan</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if(isset($schedules) && $schedules->count() > 0)
                        @foreach($schedules as $schedule)
                        <tr class="hover:bg-orange-50 transition">
                            <!-- NAMA -->
                            <td class="px-4 py-3">{{ $schedule->user->name }}</td>
                            <td class="px-4 py-3">{{ $schedule->day_name }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $schedule->user->division->division_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $schedule->activity }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $schedule->description ?? '-' }}</td>
                            <!-- STATUS -->
                            <td class="px-4 py-3">
                                @if($schedule->status == 'Pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pending</span>
                                @elseif($schedule->status == 'Done')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Done</span>
                                @elseif($schedule->status == 'Alpha')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Alpha</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                        {{ $schedule->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Belum ada jadwal piket
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bellButton = document.getElementById('bellButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (bellButton && notificationDropdown) {
        bellButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function(e) {
            if (!bellButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
        
        notificationDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
@endpush

@endsection