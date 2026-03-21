@extends('layouts.app')

@section('title', 'Picket Journal Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Picket Journal Management</h1>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                {{ $journals->total() }} jadwal
            </span>
            
            
            <a href="{{ route('admin.picketjournal.create') }}" 
               class="bg-[#F9BC60] text-[#004643] px-4 py-2 rounded-lg hover:bg-[#e5a94d] flex items-center gap-2 transition duration-200">
                <span class="material-symbols-outlined text-base">add</span>
                Tambah Piket
            </a>
            
            
            <a href="{{ route('admin.picketjournal.exportPdf', request()->query()) }}" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center gap-2 transition duration-200">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                Export PDF
            </a>
        </div>
    </div>

    
    <div class="mb-6">
        <p class="text-gray-600 mb-3 text-sm">Filter details... (cari nama, kelas, atau deskripsi)</p>
        
        
        <form method="GET" action="{{ route('admin.picketjournal.index') }}" class="space-y-4">
            
            <div class="flex gap-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi..." 
                       class="flex-1 border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent">
                
                
                <select name="week" class="border rounded-lg p-2.5 min-w-[220px] bg-white">
                    <option value="">SEMUA MINGGU</option>
                    @foreach($weeks as $value => $label)
                        <option value="{{ $value }}" {{ request('week') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-[#F9BC60] text-[#004643] px-6 py-2.5 rounded-lg hover:bg-[#e5a94d] font-medium">
                    Filter
                </button>
            </div>

            
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.picketjournal.index', array_merge(request()->except('day'), ['day' => ''])) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition duration-200 
                          {{ !request('day') ? 'bg-[#F9BC60] text-[#004643] shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    SEMUA HARI
                </a>
                @foreach(['Monday' => 'SENIN', 'Tuesday' => 'SELASA', 'Wednesday' => 'RABU', 'Thursday' => 'KAMIS', 'Friday' => 'JUMAT'] as $value => $label)
                <a href="{{ route('admin.picketjournal.index', array_merge(request()->except('day'), ['day' => $value])) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition duration-200 
                          {{ request('day') == $value ? 'bg-[#F9BC60] text-[#004643] shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            
            @if(request()->anyFilled(['search', 'week', 'day']))
            <div class="mt-2">
                <a href="{{ route('admin.picketjournal.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">close</span>
                    Reset semua filter
                </a>
            </div>
            @endif
        </form>
    </div>

    
    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Nama</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Hari</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Tanggal</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Divisi</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Deskripsi</th>
                    <th class="border-b p-3 text-center font-semibold text-gray-700">Detail</th>
                    <th class="border-b p-3 text-center font-semibold text-gray-700">Edit</th>
                    <th class="border-b p-3 text-center font-semibold text-gray-700">Status</th>
                 </tr>
            </thead>
            <tbody>
                @forelse($journals as $journal)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="p-3 font-medium text-gray-800">{{ $journal->user->name }}</td>
                    <td class="p-3 text-gray-600">{{ $journal->date->locale('id')->isoFormat('dddd') }}</td>
                    <td class="p-3 text-gray-600">{{ $journal->date->format('d/m/Y') }}</td>
                    <td class="p-3">
                        @if($journal->user->division)
                            <span class="px-2 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-medium">
                                {{ $journal->user->division->division_name }}
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="p-3 max-w-xs">
                        <div class="truncate text-gray-700" title="{{ $journal->activity }}">
                            {{ Str::limit($journal->activity, 40) }}
                        </div>
                        @if($journal->description)
                            <div class="text-xs text-gray-500 truncate mt-0.5">{{ Str::limit($journal->description, 30) }}</div>
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('admin.picketjournal.show', $journal->id) }}" 
                           class="text-blue-500 hover:text-blue-700 inline-block transition" title="Detail">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </a>
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('admin.picketjournal.edit', $journal->id) }}" 
                           class="text-[#F9BC60] hover:text-[#e5a94d] inline-block transition" title="Edit">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </a>
                    </td>
                    <td class="p-3 text-center">
                        @include('admin.picketjournal.partials.status-badge', ['status' => $journal->status])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl mb-3 text-gray-300">assignment_late</span>
                        <p class="text-lg font-medium">No Data</p>
                        <p class="text-sm mt-1">Belum ada jurnal piket</p>
                        <a href="{{ route('admin.picketjournal.create') }}" 
                           class="inline-block mt-4 bg-[#F9BC60] text-[#004643] px-4 py-2 rounded-lg hover:bg-[#e5a94d] transition">
                            + Tambah Piket Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    
    @if($journals->hasPages())
    <div class="mt-4">
        {{ $journals->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection