{{-- resources/views/admin/notification/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin - Manage Notifications')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Manage Notifications</h1>
        <button onclick="openBroadcastModal()" 
                class="bg-[#F9BC60] text-[#004643] px-4 py-2 rounded-lg hover:bg-[#e5a94d]">
            + Broadcast Notifikasi
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_notifications'] ?? 0 }}</div>
            <div class="text-sm text-gray-600">Total Notifikasi</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['unread_notifications'] ?? 0 }}</div>
            <div class="text-sm text-gray-600">Belum Dibaca</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <div class="text-2xl font-bold text-green-600">{{ $stats['notifications_today'] ?? 0 }}</div>
            <div class="text-sm text-gray-600">Hari Ini</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['users_with_notifications'] ?? 0 }}</div>
            <div class="text-sm text-gray-600">User Aktif</div>
        </div>
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="grid grid-cols-4 gap-4 mb-6">
        <select name="user_id" class="border rounded-lg p-2">
            <option value="">Semua User</option>
            @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>

        <input type="date" name="date" value="{{ request('date') }}" class="border rounded-lg p-2">

        <select name="status" class="border rounded-lg p-2">
            <option value="">Semua Status</option>
            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
        </select>

        <button type="submit" class="bg-[#F9BC60] text-[#004643] rounded-lg p-2 hover:bg-[#e5a94d]">
            Filter
        </button>
    </form>

    {{-- Actions --}}
    <div class="flex justify-end mb-4">
        <form action="{{ route('admin.notification.clearRead') }}" method="POST" 
              onsubmit="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:underline">
                Hapus semua yang sudah dibaca
            </button>
        </form>
    </div>

    {{-- Notifications Table --}}
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-3 text-left">No</th>
                    <th class="border p-3 text-left">User</th>
                    <th class="border p-3 text-left">Type</th>
                    <th class="border p-3 text-left">Message</th>
                    <th class="border p-3 text-left">Status</th>
                    <th class="border p-3 text-left">Waktu</th>
                    <th class="border p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications ?? [] as $index => $notification)
                @php
                    $data = json_decode($notification->data, true);
                    $user = $users->firstWhere('id', $notification->notifiable_id);
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="border p-3">{{ $index + 1 }}</td>
                    <td class="border p-3">
                        <div class="font-medium">{{ $user->name ?? 'Unknown' }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email ?? '' }}</div>
                    </td>
                    <td class="border p-3">{{ $data['action'] ?? 'unknown' }}</td>
                    <td class="border p-3 max-w-xs">
                        <div class="truncate">{{ $data['message'] ?? 'No message' }}</div>
                    </td>
                    <td class="border p-3">
                        @if($notification->read_at)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Dibaca</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Belum Dibaca</span>
                        @endif
                    </td>
                    <td class="border p-3">
                        <div>{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}</div>
                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                    </td>
                    <td class="border p-3 text-center">
                        <a href="{{ route('admin.notification.show', $notification->id) }}" 
                           class="text-blue-600 hover:text-blue-800 mr-2">
                            <span class="material-symbols-outlined text-base">visibility</span>
                        </a>
                        <form action="{{ route('admin.notification.destroy', $notification->id) }}" 
                              method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Hapus notifikasi?')">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="border p-8 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl mb-4">notifications_none</span>
                        <p class="text-lg">Belum ada notifikasi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $notifications->appends(request()->query())->links() }}
    </div>
</div>

{{-- Modal Broadcast --}}
<div id="broadcastModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-lg w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-semibold">Broadcast Notifikasi</h3>
        </div>
        <form action="{{ route('admin.notification.broadcast') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block mb-2">Pesan</label>
                    <textarea name="message" rows="4" required 
                              class="w-full border rounded-lg p-2"
                              placeholder="Masukkan pesan notifikasi..."></textarea>
                </div>
                <div>
                    <label class="block mb-2">Tipe</label>
                    <select name="type" class="w-full border rounded-lg p-2">
                        <option value="info">Info (Biru)</option>
                        <option value="success">Success (Hijau)</option>
                        <option value="warning">Warning (Kuning)</option>
                    </select>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg text-sm text-yellow-800">
                    <span class="font-medium">Info:</span> Notifikasi akan dikirim ke semua user aktif.
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-2">
                <button type="button" onclick="closeBroadcastModal()" 
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d]">
                    Kirim Broadcast
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBroadcastModal() {
    document.getElementById('broadcastModal').classList.remove('hidden');
    document.getElementById('broadcastModal').classList.add('flex');
}

function closeBroadcastModal() {
    document.getElementById('broadcastModal').classList.add('hidden');
    document.getElementById('broadcastModal').classList.remove('flex');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('broadcastModal')) {
        closeBroadcastModal();
    }
}
</script>
@endsection