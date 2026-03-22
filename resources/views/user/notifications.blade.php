@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi Saya</h1>
            <a href="{{ route('dashboard') }}" class="text-orange-500 hover:text-orange-600">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total Notifikasi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Notifikasi</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalNotifications ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Belum Dibaca -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Belum Dibaca</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $unreadCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sudah Dibaca -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Sudah Dibaca</p>
                    <p class="text-3xl font-bold text-green-600">{{ $readCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="bg-white rounded-lg shadow mb-6 p-4 flex justify-end gap-3">
        <form action="{{ route('user.notifications.read-all') }}" method="POST" class="inline">
            @csrf
            @method('PUT')
            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm">
                Tandai semua dibaca
            </button>
        </form>
        <form action="{{ route('user.notifications.delete-read') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm" onclick="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
                Hapus yang sudah dibaca
            </button>
        </form>
    </div>

    <!-- Tabel Notifikasi - Rapi dengan Border -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border-b border-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                        <th class="border-b border-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PESAN</th>
                        <th class="border-b border-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                        <th class="border-b border-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WAKTU</th>
                        <th class="border-b border-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $index => $notification)
                    <tr class="hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-yellow-50' : '' }}">
                        <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-500">{{ $index + $notifications->firstItem() }}</td>
                        <td class="border-b border-gray-200 px-6 py-4">
                            <p class="text-sm text-gray-900">
                                {{ $notification->data['message'] ?? 'Tidak ada pesan' }}
                            </p>
                            @if(isset($notification->data['sender']))
                                <p class="text-xs text-gray-400 mt-1">Dari: {{ $notification->data['sender'] }}</p>
                            @endif
                        </td>
                        <td class="border-b border-gray-200 px-6 py-4">
                            @if(is_null($notification->read_at))
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    Belum Dibaca
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Sudah Dibaca
                                </span>
                            @endif
                        </td>
                        <td class="border-b border-gray-200 px-6 py-4 text-sm text-gray-500">
                            <div>{{ $notification->created_at->format('d/m/Y H:i') }}</div>
                            <div class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="border-b border-gray-200 px-6 py-4">
                            @if(is_null($notification->read_at))
                                <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Tandai sudah dibaca">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">Sudah dibaca</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="border-b border-gray-200 px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p>Belum ada notifikasi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection