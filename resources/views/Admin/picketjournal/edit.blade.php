@extends('layouts.app')

@section('title', 'Edit Jurnal')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Edit Jurnal</h1>
        <div class="text-sm bg-gray-100 px-3 py-1 rounded-full">
            ID: PKT-{{ str_pad($journal->id, 7, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <form action="{{ route('admin.picketjournal.update', $journal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- DATA USER (READONLY) --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama User</label>
                    <input type="text" value="{{ $journal->user->name }}" readonly class="w-full bg-gray-100 border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                    <input type="text" value="{{ $journal->user->division->division_name ?? '-' }}" readonly class="w-full bg-gray-100 border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    {{-- Menggunakan Carbon parse untuk keamanan --}}
                    <input type="text" value="{{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}" readonly class="w-full bg-gray-100 border rounded-lg p-2">
                </div>
            </div>

            {{-- FORM EDIT --}}
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        {{-- FIX: Cek jika string, maka parse dulu --}}
                        <input type="time" name="start_time" 
                               value="{{ $journal->start_time ? \Carbon\Carbon::parse($journal->start_time)->format('H:i') : '07:00' }}"
                               class="w-full border rounded-lg p-2 @error('start_time') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" 
                               value="{{ $journal->end_time ? \Carbon\Carbon::parse($journal->end_time)->format('H:i') : '08:00' }}"
                               class="w-full border rounded-lg p-2 @error('end_time') border-red-500 @enderror">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tugas</label>
                    <textarea name="activity" rows="3" class="w-full border rounded-lg p-2 @error('activity') border-red-500 @enderror">{{ old('activity', $journal->activity) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg p-2 bg-white">
                        <option value="Done" {{ old('status', $journal->status) == 'Done' ? 'selected' : '' }}>Done</option>
                        <option value="Pending" {{ old('status', $journal->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="OnGoing" {{ old('status', $journal->status) == 'OnGoing' ? 'selected' : '' }}>OnGoing</option>
                        <option value="Sakit" {{ old('status', $journal->status) == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ old('status', $journal->status) == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alpha" {{ old('status', $journal->status) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- BAGIAN FOTO --}}
        <div class="mt-6 border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-3">Gambar Bukti</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sebelum</label>
                    @if($journal->before_photo)
                        @php
                            $pathBefore = Str::contains($journal->before_photo, 'picket_photos') ? asset('storage/'.$journal->before_photo) : asset('images/'.$journal->before_photo);
                        @endphp
                        <img src="{{ $pathBefore }}" class="w-full h-40 object-cover rounded-lg mb-2 border">
                    @endif
                    <!-- <input type="file" name="before_photo" accept="image/*" class="w-full border rounded-lg p-2 text-sm"> -->
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sesudah</label>
                    @if($journal->after_photo)
                        @php
                            $pathAfter = Str::contains($journal->after_photo, 'picket_photos') ? asset('storage/'.$journal->after_photo) : asset('images/'.$journal->after_photo);
                        @endphp
                        <img src="{{ $pathAfter }}" class="w-full h-40 object-cover rounded-lg mb-2 border">
                    @endif
                    <!-- <input type="file" name="after_photo" accept="image/*" class="w-full border rounded-lg p-2 text-sm"> -->
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-6 border-t pt-4">
            <a href="{{ route('admin.picketjournal.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</a>
            <button type="submit" class="px-6 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d] font-bold transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection