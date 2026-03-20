{{-- resources/views/Admin/picketjournal/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Picket Journal Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Picket Journal Management</h1>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">{{ $journals->total() }} jadwal</span>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6">
        <p class="text-gray-600 mb-2">Filter details... (cari nama, kelas, atau deskripsi)</p>
        
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.picketjournal.index') }}" class="space-y-4">
            {{-- Search Row --}}
            <div class="flex gap-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi..." 
                       class="flex-1 border rounded-lg p-2 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent">

                {{-- Week Filter Dropdown --}}
                <select name="week" class="border rounded-lg p-2 min-w-[200px]">
                    <option value="">SEMUA MINGGU</option>
                    @foreach($weeks as $value => $label)
                        <option value="{{ $value }}" {{ request('week') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-[#F9BC60] text-[#004643] px-6 py-2 rounded-lg hover:bg-[#e5a94d]">
                    Filter
                </button>
            </div>

            {{-- Day Chips --}}
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.picketjournal.index', array_merge(request()->except('day'), ['day' => ''])) }}" 
                   class="px-4 py-2 rounded-full text-sm {{ !request('day') ? 'bg-[#F9BC60] text-[#004643]' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    SEMUA HARI
                </a>
                @foreach(['Monday' => 'SENIN', 'Tuesday' => 'SELASA', 'Wednesday' => 'RABU', 'Thursday' => 'KAMIS', 'Friday' => 'JUMAT'] as $value => $label)
                <a href="{{ route('admin.picketjournal.index', array_merge(request()->except('day'), ['day' => $value])) }}" 
                   class="px-4 py-2 rounded-full text-sm {{ request('day') == $value ? 'bg-[#F9BC60] text-[#004643]' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Reset Filter Link --}}
            @if(request()->anyFilled(['search', 'week', 'day']))
            <div class="mt-2">
                <a href="{{ route('admin.picketjournal.index') }}" class="text-sm text-blue-600 hover:underline">
                    ⨯ Reset semua filter
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-3 text-left">Nama</th>
                    <th class="border p-3 text-left">Hari</th>
                    <th class="border p-3 text-left">Tanggal</th>
                    <th class="border p-3 text-left">Divisi</th>
                    <th class="border p-3 text-left">Deskripsi</th>
                    <th class="border p-3 text-center">Detail</th>
                    <th class="border p-3 text-center">Edit</th>
                    <th class="border p-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journals as $journal)
                <tr class="hover:bg-gray-50">
                    <td class="border p-3 font-medium">{{ $journal->user->name }}</td>
                    <td class="border p-3">{{ $journal->date->locale('id')->isoFormat('dddd') }}</td>
                    <td class="border p-3">{{ $journal->date->format('d/m/Y') }}</td>
                    <td class="border p-3">
                        @if($journal->user->division)
                            {{ $journal->user->division->division_name }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="border p-3 max-w-xs">
                        <div class="truncate" title="{{ $journal->activity }}">
                            {{ Str::limit($journal->activity, 50) }}
                        </div>
                        @if($journal->description)
                            <div class="text-xs text-gray-500 truncate">{{ Str::limit($journal->description, 30) }}</div>
                        @endif
                    </td>
                    <td class="border p-3 text-center">
                        <a href="{{ route('admin.picketjournal.show', $journal->id) }}" 
                           class="text-blue-600 hover:text-blue-800 inline-block">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                    </td>
                    <td class="border p-3 text-center">
                        <a href="{{ route('admin.picketjournal.edit', $journal->id) }}" 
                           class="text-[#F9BC60] hover:text-[#e5a94d] inline-block">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </td>
                    <td class="border p-3 text-center">
                        @include('admin.picketjournal.partials.status-badge', ['status' => $journal->status])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="border p-8 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl mb-4">assignment_late</span>
                        <p class="text-lg">No Data</p>
                        <p class="text-sm mt-2">Belum ada jurnal piket</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $journals->appends(request()->query())->links() }}
    </div>
</div>
@endsection