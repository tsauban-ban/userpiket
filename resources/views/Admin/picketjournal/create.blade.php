

@extends('layouts.app')

@section('title', 'Tambah Picket Journal')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
    
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Tambah Picket Journal</h1>
        <a href="{{ route('admin.picketjournal.index') }}" 
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 flex items-center gap-2">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali
        </a>
    </div>

    
    
    <form action="{{ route('admin.picketjournal.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-2 gap-6">
            
            
            <div class="space-y-4">
                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama User <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" required 
                            class="w-full border rounded-lg p-2 @error('user_id') border-red-500 @enderror">
                        <option value="">Pilih User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->division->division_name ?? 'Tanpa Divisi' }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" 
                           required class="w-full border rounded-lg p-2 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}" 
                           class="w-full border rounded-lg p-2" placeholder="Contoh: Ruang IT, Lab Komputer">
                </div>
            </div>

            
            
            <div class="space-y-4">
                
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time', '07:00') }}"
                               class="w-full border rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time', '08:00') }}"
                               class="w-full border rounded-lg p-2">
                    </div>
                </div>

                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Activity / Deskripsi Tugas <span class="text-red-500">*</span>
                    </label>
                    <textarea name="activity" rows="3" required
                              class="w-full border rounded-lg p-2 @error('activity') border-red-500 @enderror"
                              placeholder="Contoh: Piket Kebersihan - Membersihkan ruang kelas">{{ old('activity') }}</textarea>
                    @error('activity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tambahan</label>
                    <textarea name="description" rows="2" 
                              class="w-full border rounded-lg p-2"
                              placeholder="Catatan tambahan jika ada...">{{ old('description') }}</textarea>
                </div>

                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full border rounded-lg p-2">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                        <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alpha" {{ old('status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                        <option value="Terlambat" {{ old('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        
        
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
            <textarea name="notes" rows="2" 
                      class="w-full border rounded-lg p-2"
                      placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
        </div>

        
        
        <div class="mt-6 border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-3">Foto Dokumentasi (Opsional)</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sebelum</label>
                    <input type="file" name="before_photo" accept="image/*"
                           class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Max. 5MB. Format: JPG, PNG, JPEG</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sesudah</label>
                    <input type="file" name="after_photo" accept="image/*"
                           class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Max. 5MB. Format: JPG, PNG, JPEG</p>
                </div>
            </div>
        </div>

        
        
        <div class="flex justify-end gap-2 mt-6 border-t pt-4">
            <a href="{{ route('admin.picketjournal.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d] transition flex items-center gap-2">
                <span class="material-symbols-outlined text-base">save</span>
                Simpan Jurnal
            </button>
        </div>
    </form>
</div>
@endsection