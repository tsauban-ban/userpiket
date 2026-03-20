@extends('layouts.app')

@section('title', 'Manage Divisions')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manage Divisions</h2>
        <button onclick="openAddModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Division
        </button>
    </div>

    {{-- Grid Divisions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($divisions as $division)
        <div class="border rounded-lg shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="p-5 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold">{{ $division->division_name }}</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Total Users: {{ $division->users_count ?? 0 }} users
                </p>
            </div>

            {{-- Card Body --}}
            <div class="p-5">
                {{-- Preview Users (max 3) --}}
                @php
                    $users = $division->users->take(3);
                @endphp
                
                @if($users->count() > 0)
                    <div class="space-y-2 mb-4">
                        @foreach($users as $user)
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center text-white text-xs">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </div>
                        @endforeach
                        
                        @if($division->users_count > 3)
                            <p class="text-xs text-gray-500 mt-2">
                                dan {{ $division->users_count - 3 }} user lainnya...
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-gray-400 text-sm mb-4">Belum ada user di divisi ini</p>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('divisions.show', $division->id) }}" 
                       class="bg-pink-500 text-white px-4 py-1.5 rounded text-sm hover:bg-pink-600">
                        Detail
                    </a>
                    <button onclick="openEditModal({{ $division->id }}, '{{ $division->division_name }}')"
                            class="bg-blue-500 text-white px-4 py-1.5 rounded text-sm hover:bg-blue-600">
                        Edit
                    </button>
                    <button onclick="openDeleteModal({{ $division->id }}, '{{ $division->division_name }}')"
                            class="border border-red-500 text-red-500 px-4 py-1.5 rounded text-sm hover:bg-red-50">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-12 text-gray-500">
            <div class="text-6xl mb-4">📂</div>
            <p class="text-lg">Belum ada divisi</p>
            <p class="text-sm mt-2">Klik "Add Division" untuk menambah divisi baru</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL ADD DIVISION --}}
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-semibold">Tambah Divisi Baru</h3>
        </div>
        <form action="{{ route('divisions.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <label class="block mb-2">Nama Divisi</label>
                <input type="text" name="division_name" required 
                       class="w-full border rounded p-2"
                       placeholder="Contoh: Macro">
            </div>
            <div class="p-6 border-t flex justify-end gap-2">
                <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT DIVISION --}}
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6 border-b">
            <h3 class="text-xl font-semibold">Edit Divisi</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="p-6">
                <label class="block mb-2">Nama Divisi</label>
                <input type="text" name="division_name" id="edit_division_name" required 
                       class="w-full border rounded p-2">
            </div>
            <div class="p-6 border-t flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DELETE DIVISION --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="text-6xl text-red-500 mb-4">⚠️</div>
                <h3 class="text-xl font-semibold">Hapus Divisi</h3>
                <p class="text-gray-600 mt-2">
                    Apakah Anda yakin ingin menghapus divisi <span id="deleteDivisionName" class="font-bold"></span>?
                </p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add Modal
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
}

// Edit Modal
function openEditModal(id, name) {
    document.getElementById('edit_division_name').value = name;
    document.getElementById('editForm').action = '/divisions/' + id;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

// Delete Modal
function openDeleteModal(id, name) {
    document.getElementById('deleteDivisionName').textContent = name;
    document.getElementById('deleteForm').action = '/divisions/' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('addModal')) closeAddModal();
    if (event.target == document.getElementById('editModal')) closeEditModal();
    if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
}
</script>
@endsection