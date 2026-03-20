@extends('layouts.app')

@section('title', 'Tambah Jurnal Piket')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Tambah Jurnal Piket</h2>

    <form action="{{ route('admin.picketjournal.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-2">User</label>
            <select name="user_id" required class="w-full border rounded p-2 @error('user_id') border-red-500 @enderror">
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2">Tanggal</label>
            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" 
                   required class="w-full border rounded p-2 @error('date') border-red-500 @enderror">
            @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2">Activity</label>
            <input type="text" name="activity" value="{{ old('activity') }}" 
                   required class="w-full border rounded p-2 @error('activity') border-red-500 @enderror">
            @error('activity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2">Description</label>
            <textarea name="description" rows="3" 
                      class="w-full border rounded p-2">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Status</label>
            <select name="status" required class="w-full border rounded p-2">
                <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                <option value="Alpha" {{ old('status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                <option value="Terlambat" {{ old('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Notes (Opsional)</label>
            <textarea name="notes" rows="2" 
                      class="w-full border rounded p-2">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.picketjournal.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection