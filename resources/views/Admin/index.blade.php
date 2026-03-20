{{-- resources/views/admin/picketjournal/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Picket Journal')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Picket Journal Management</h2>
        <div class="flex gap-2">
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Unduh Excel
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6">
        <p class="text-gray-600 mb-2">Filter details... (cari nama, kelas, atau absen)</p>
        <div class="flex gap-4">
            <input type="text" 
                   placeholder="Cari..." 
                   class="border rounded p-2 flex-1"
                   id="searchInput">
            
            <select class="border rounded p-2" id="filterKelas">
                <option value="">Semua Kelas</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
            </select>
            
            <select class="border rounded p-2" id="filterStatus">
                <option value="">Semua Status</option>
                <option value="Hadir">Hadir</option>
                <option value="Sakit">Sakit</option>
                <option value="Izin">Izin</option>
                <option value="Alpha">Alpha</option>
            </select>
            
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filter
            </button>
        </div>
    </div>

    {{-- Title --}}
    <h3 class="text-xl font-semibold mb-4">Management Picket Journal</h3>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-3 text-left">Nama</th>
                    <th class="border p-3 text-left">Description</th>
                    <th class="border p-3 text-center">Edit</th>
                    <th class="border p-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journals as $journal)
                <tr>
                    <td class="border p-3">{{ $journal->user->name }}</td>
                    <td class="border p-3">
                        @if($journal->user->division)
                            {{ $journal->user->division->division_name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="border p-3">
                        <div class="font-medium">{{ $journal->activity }}</div>
                        @if($journal->description)
                            <div class="text-sm text-gray-600">{{ $journal->description }}</div>
                        @endif
                        @if($journal->notes)
                            <div class="text-xs text-gray-500 mt-1">Note: {{ $journal->notes }}</div>
                        @endif
                    </td>
                    <td class="border p-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.picketjournal.edit', $journal->id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.picketjournal.destroy', $journal->id) }}" 
                                  method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                        onclick="return confirm('Yakin?')">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="border p-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($journal->status == 'Hadir') bg-green-200 text-green-800
                            @elseif($journal->status == 'Sakit') bg-yellow-200 text-yellow-800
                            @elseif($journal->status == 'Izin') bg-blue-200 text-blue-800
                            @elseif($journal->status == 'Terlambat') bg-orange-200 text-orange-800
                            @else bg-red-200 text-red-800
                            @endif">
                            {{ $journal->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border p-8 text-center text-gray-500">
                        <div class="text-6xl mb-4">📭</div>
                        <div class="text-xl">No Data</div>
                        <p class="text-sm mt-2">Belum ada jurnal piket</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $journals->links() }}
    </div>
</div>

<script>
// Simple filter functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    // Implementasi filter bisa ditambahkan nanti
    console.log('Search:', this.value);
});
</script>
@endsection