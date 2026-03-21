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

        <div class="grid grid-cols-2 gap-6">
            
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama User</label>
                    <input type="text" value="{{ $journal->user->name }}" readonly 
                           class="w-full bg-gray-100 border rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor</label>
                    <input type="text" value="{{ $journal->id }}" readonly 
                           class="w-full bg-gray-100 border rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                    <input type="text" value="{{ $journal->user->division->division_name ?? '-' }}" readonly 
                           class="w-full bg-gray-100 border rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="text" value="{{ $journal->date->format('d/m/Y') }}" readonly 
                           class="w-full bg-gray-100 border rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <input type="text" value="{{ $journal->date->locale('id')->isoFormat('dddd') }}" readonly 
                           class="w-full bg-gray-100 border rounded-lg p-2">
                </div>
            </div>

            
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" 
                               value="{{ $journal->start_time ? $journal->start_time->format('H:i') : '07:00' }}"
                               class="w-full border rounded-lg p-2 @error('start_time') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" 
                               value="{{ $journal->end_time ? $journal->end_time->format('H:i') : '08:00' }}"
                               class="w-full border rounded-lg p-2 @error('end_time') border-red-500 @enderror">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tugas</label>
                    <textarea name="activity" rows="3" 
                              class="w-full border rounded-lg p-2 @error('activity') border-red-500 @enderror">{{ old('activity', $journal->activity) }}</textarea>
                    @error('activity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea name="description" rows="2" 
                              class="w-full border rounded-lg p-2">{{ old('description', $journal->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="space-y-2 border rounded-lg p-3">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="status" value="done" 
                                   {{ old('status', $journal->status) == 'done' ? 'checked' : '' }}>
                            <span>Done</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="status" value="pending" 
                                   {{ old('status', $journal->status) == 'pending' ? 'checked' : '' }}>
                            <span>Pending</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="status" value="approved" 
                                   {{ old('status', $journal->status) == 'approved' ? 'checked' : '' }}>
                            <span>Sakit</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="status" value="rejected" 
                                   {{ old('status', $journal->status) == 'rejected' ? 'checked' : '' }}>
                            <span>Izin</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="status" value="rejected" 
                                   {{ old('status', $journal->status) == 'rejected' ? 'checked' : '' }}>
                            <span>Alpha</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="status" value="rejected" 
                                   {{ old('status', $journal->status) == 'rejected' ? 'checked' : '' }}>
                            <span>Terlambat</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        
        
        <div class="mt-6 border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-3">Gambar</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sebelum</label>
                    @if($journal->before_photo)
                        <img src="{{ asset('storage/' . $journal->before_photo) }}" 
                             class="w-full h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="before_photo" accept="image/*"
                           class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Max. 5MB</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sesudah</label>
                    @if($journal->after_photo)
                        <img src="{{ asset('storage/' . $journal->after_photo) }}" 
                             class="w-full h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="after_photo" accept="image/*"
                           class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Max. 5MB</p>
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