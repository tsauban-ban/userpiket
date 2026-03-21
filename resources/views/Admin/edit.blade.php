

@extends('layouts.app')

@section('title', 'Edit Jurnal Piket')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
    
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Edit Jurnal Piket</h1>
        <div class="text-sm bg-gray-100 px-3 py-1 rounded-full">
            ID: PKT-{{ str_pad($journal->id, 7, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    
    
    <form action="{{ route('admin.picketjournal.update', $journal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6">
            
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama User</label>
                    <select name="user_id" class="w-full border rounded-lg p-2 @error('user_id') border-red-500 @enderror">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $journal->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $journal->date->format('Y-m-d') }}" 
                           class="w-full border rounded-lg p-2 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ $journal->location }}"
                           class="w-full border rounded-lg p-2" placeholder="Contoh: Ruang IT">
                </div>
            </div>

            
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" 
                               value="{{ $journal->start_time ? Carbon\Carbon::parse($journal->start_time)->format('H:i') : '07:00' }}"
                               class="w-full border rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" 
                               value="{{ $journal->end_time ? Carbon\Carbon::parse($journal->end_time)->format('H:i') : '08:00' }}"
                               class="w-full border rounded-lg p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activity</label>
                    <input type="text" name="activity" value="{{ $journal->activity }}"
                           class="w-full border rounded-lg p-2 @error('activity') border-red-500 @enderror">
                    @error('activity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" 
                              class="w-full border rounded-lg p-2">{{ $journal->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg p-2">
                        <option value="pending" {{ $journal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="done" {{ $journal->status == 'done' ? 'selected' : '' }}>Done</option>
                        <option value="Hadir" {{ $journal->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Sakit" {{ $journal->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ $journal->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alpha" {{ $journal->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                        <option value="Terlambat" {{ $journal->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
            </div>
        </div>

        
        
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
            <textarea name="notes" rows="2" class="w-full border rounded-lg p-2">{{ $journal->notes }}</textarea>
        </div>

        
        
        <div class="mt-6 border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-3">Foto</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sebelum</label>
                    @if($journal->before_photo)
                        <img src="{{ asset('storage/' . $journal->before_photo) }}" 
                             class="w-full h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="before_photo" accept="image/*"
                           class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Max. 5MB. Kosongkan jika tidak ingin mengubah.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sesudah</label>
                    @if($journal->after_photo)
                        <img src="{{ asset('storage/' . $journal->after_photo) }}" 
                             class="w-full h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="after_photo" accept="image/*"
                           class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Max. 5MB. Kosongkan jika tidak ingin mengubah.</p>
                </div>
            </div>
        </div>

        
        
        <div class="flex justify-end gap-2 mt-6 border-t pt-4">
            <a href="{{ route('admin.picketjournal.show', $journal->id) }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d]">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection