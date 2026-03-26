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

    <!-- HEADER DIVISI -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
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
            
            <!-- Logout -->
            <div class="flex items-center gap-3">
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Notifikasi dropdown
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