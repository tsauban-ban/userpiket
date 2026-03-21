

@extends('layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
    
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Detail Notifikasi</h1>
        <a href="{{ route('admin.notification.index') }}" 
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
            Kembali
        </a>
    </div>

    @php
        $data = json_decode($notification->data, true);
    @endphp

    

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi Notifikasi</h3>
            <table class="w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-1/3">ID</td>
                    <td class="py-2 font-medium">: {{ $notification->id }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Tipe</td>
                    <td class="py-2 font-medium">: {{ $notification->type }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Dibuat</td>
                    <td class="py-2 font-medium">: {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Status</td>
                    <td class="py-2">: 
                        @if($notification->read_at)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Dibaca {{ \Carbon\Carbon::parse($notification->read_at)->diffForHumans() }}</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Belum Dibaca</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi User</h3>
            <table class="w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-1/3">Nama</td>
                    <td class="py-2 font-medium">: {{ $user->name ?? 'Unknown' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Email</td>
                    <td class="py-2 font-medium">: {{ $user->email ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Role</td>
                    <td class="py-2 font-medium">: {{ $user->role ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    
    
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Data Notifikasi</h3>
        <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto text-sm">
{{ json_encode($data, JSON_PRETTY_PRINT) }}
        </pre>
    </div>

    
    
    <div class="flex justify-end gap-2 border-t pt-4">
        <form action="{{ route('admin.notification.destroy', $notification->id) }}" method="POST"
              onsubmit="return confirm('Hapus notifikasi ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Hapus Notifikasi
            </button>
        </form>
    </div>
</div>
@endsection