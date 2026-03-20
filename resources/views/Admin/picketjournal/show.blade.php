@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Detail Jadwal</h1>
        <div class="text-sm bg-gray-100 px-3 py-1 rounded-full">
            ID: PKT-{{ str_pad($journal->id, 7, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    {{-- Informasi Jadwal --}}
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi Jadwal</h3>
            <table class="w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-1/3">Lokasi</td>
                    <td class="py-2 font-medium">: {{ $journal->location ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Tanggal</td>
                    <td class="py-2 font-medium">: {{ $journal->date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Status</td>
                    <td class="py-2">: @include('admin.picketjournal.partials.status-badge', ['status' => $journal->status])</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Waktu</td>
                    <td class="py-2 font-medium">: 
                        {{ $journal->start_time ? Carbon\Carbon::parse($journal->start_time)->format('H:i') : '07:00' }} - 
                        {{ $journal->end_time ? Carbon\Carbon::parse($journal->end_time)->format('H:i') : '08:00' }} WIB
                    </td>
                </tr>
            </table>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi User</h3>
            <table class="w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-1/3">Nama</td>
                    <td class="py-2 font-medium">: {{ $journal->user->name }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Divisi</td>
                    <td class="py-2 font-medium">: {{ $journal->user->division->division_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Hari</td>
                    <td class="py-2 font-medium">: {{ $journal->date->locale('id')->isoFormat('dddd') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Deskripsi Tugas --}}
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Deskripsi Tugas</h3>
        <p class="text-gray-800 font-medium">{{ $journal->activity }}</p>
        @if($journal->description)
            <p class="text-gray-600 mt-2">{{ $journal->description }}</p>
        @endif
    </div>

    {{-- Gambar Terupload --}}
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Gambar Terupload</h3>
        
        <div class="grid grid-cols-2 gap-4">
            {{-- Foto Sebelum --}}
            <div>
                <h4 class="text-sm font-medium text-gray-600 mb-2">Foto Sebelum</h4>
                @if($journal->before_photo)
                    <img src="{{ asset('storage/' . $journal->before_photo) }}" 
                         alt="Before Photo" 
                         class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                @else
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                        <span class="material-symbols-outlined text-gray-400 text-5xl">image</span>
                        <p class="text-sm text-gray-500 mt-2">Gambar belum diupload</p>
                    </div>
                @endif
            </div>

            {{-- Foto Sesudah --}}
            <div>
                <h4 class="text-sm font-medium text-gray-600 mb-2">Foto Sesudah</h4>
                @if($journal->after_photo)
                    <img src="{{ asset('storage/' . $journal->after_photo) }}" 
                         alt="After Photo" 
                         class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                @else
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                        <span class="material-symbols-outlined text-gray-400 text-5xl">image</span>
                        <p class="text-sm text-gray-500 mt-2">Gambar belum diupload</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex justify-between items-center border-t pt-4">
        <div class="text-sm text-gray-500">
            Terakhir diperbarui: {{ $journal->updated_at->locale('id')->isoFormat('D MMMM Y H:mm') }} WIB
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.picketjournal.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
            <a href="{{ route('admin.picketjournal.edit', $journal->id) }}" 
               class="px-4 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d]">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection