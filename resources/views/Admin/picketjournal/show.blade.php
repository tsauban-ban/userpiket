@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Detail Jadwal</h1>
        <div class="text-sm bg-gray-100 px-3 py-1 rounded-full">
            ID: PKT-{{ str_pad($journal->id, 7, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    {{-- INFORMASI UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi Jadwal</h3>
            <table class="w-full text-sm">
                <tr>
                    <td class="py-2 text-gray-600 w-1/3">Lokasi</td>
                    <td class="py-2 font-medium">: {{ $journal->location ?? 'Area Sekolah' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Tanggal</td>
                    <td class="py-2 font-medium">: {{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Status</td>
                    <td class="py-2">: @include('admin.picketjournal.partials.status-badge', ['status' => $journal->status])</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Waktu</td>
                    <td class="py-2 font-medium">: 
                        {{ $journal->start_time ? \Carbon\Carbon::parse($journal->start_time)->format('H:i') : '07:00' }} - 
                        {{ $journal->end_time ? \Carbon\Carbon::parse($journal->end_time)->format('H:i') : '08:00' }} WIB
                    </td>
                </tr>
            </table>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi User</h3>
            <table class="w-full text-sm">
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
                    <td class="py-2 font-medium">: {{ \Carbon\Carbon::parse($journal->date)->locale('id')->isoFormat('dddd') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- DESKRIPSI --}}
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-700 mb-2">Deskripsi Tugas & Catatan</h3>
        <p class="text-gray-800 font-medium">{{ $journal->activity }}</p>
        @if($journal->description)
            <p class="text-gray-600 mt-2 text-sm italic">"{{ $journal->description }}"</p>
        @endif
    </div>

    {{-- LOGIKA FOTO DUA LOKASI --}}
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-700 mb-4">Gambar Bukti Piket</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- FOTO BEFORE --}}
            <div>
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Foto Sebelum</h4>
                @if($journal->before_photo)
                    @php
                        $isNewBefore = Str::contains($journal->before_photo, 'picket_photos');
                        $srcBefore = $isNewBefore ? asset('storage/' . $journal->before_photo) : asset('images/' . $journal->before_photo);
                    @endphp
                    <div class="rounded-lg overflow-hidden border-2 border-white shadow-md">
                        <img src="{{ $srcBefore }}" class="w-full h-64 object-cover" onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ada';">
                    </div>
                @else
                    <div class="h-64 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center bg-gray-100 text-gray-400">
                        <span class="material-symbols-outlined text-4xl">hide_image</span>
                        <p class="text-xs mt-2">Belum upload</p>
                    </div>
                @endif
            </div>

            {{-- FOTO AFTER --}}
            <div>
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Foto Sesudah</h4>
                @if($journal->after_photo)
                    @php
                        $isNewAfter = Str::contains($journal->after_photo, 'picket_photos');
                        $srcAfter = $isNewAfter ? asset('storage/' . $journal->after_photo) : asset('images/' . $journal->after_photo);
                    @endphp
                    <div class="rounded-lg overflow-hidden border-2 border-white shadow-md">
                        <img src="{{ $srcAfter }}" class="w-full h-64 object-cover" onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ada';">
                    </div>
                @else
                    <div class="h-64 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center bg-gray-100 text-gray-400">
                        <span class="material-symbols-outlined text-4xl">hide_image</span>
                        <p class="text-xs mt-2">Belum upload</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- FOOTER / ACTION --}}
    <div class="flex justify-between items-center border-t pt-6">
        <div class="text-xs text-gray-400">
            Terakhir update: {{ $journal->updated_at->format('d/m/Y H:i') }}
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.picketjournal.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Kembali</a>
            <a href="{{ route('admin.picketjournal.edit', $journal->id) }}" class="px-5 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d] font-bold transition">Edit Data</a>
        </div>
    </div>
</div>
@endsection