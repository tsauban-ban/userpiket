@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="min-h-screen bg-[#b7dcd1] p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-800">
            Manage Users
        </h1>
    </div>

    <!-- Card Utama -->
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-7xl mx-auto">

        <!-- ================= FILTER SECTION ================= -->
        <div class="mb-6">
            <form method="GET" action="{{ route('manageusers.index') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama atau email..."
                               class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Dropdown Division -->
                    <div class="w-full md:w-48">
                        <select name="division"
                                onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ request('division') == $division->id ? 'selected' : '' }}>
                                    {{ $division->division_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons Group -->
                    <div class="flex gap-2">
                        <!-- Import Button -->
                        <button type="button"
                                onclick="openImportModal()"
                                class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Import
                        </button>

                        <!-- Add User Button -->
                        <button type="button"
                                onclick="openAddModal()"
                                class="px-4 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah User
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ================= NOTIFICATION ================= -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        @if(session('import_errors'))
        <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-lg">
            <p class="font-bold mb-2">Error Import:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- ================= TABLE SECTION ================= -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="py-3 px-4 text-left font-semibold text-gray-600">ID</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-600">Nama</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-600">Divisi</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-600">Email</th>
                        <th class="py-3 px-4 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-3 px-4">{{ str_pad($loop->iteration, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                        <td class="py-3 px-4">
                            @if($user->division)
                                <span class="px-2 py-1 bg-teal-100 text-teal-800 rounded-full text-xs">
                                    {{ $user->division->division_name }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs">
                                    Tanpa Divisi
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $user->id }}, {{ json_encode([
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'division_id' => $user->division_id
                                ]) }})"
                                        class="p-1.5 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                        class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            Tidak ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ================= TOTAL USER ================= -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-500 text-center">
                Total: <span class="font-semibold">{{ $users->count() }}</span> user
            </div>
        </div>

    </div>
</div>

<!-- ================= MODAL ADD USER ================= -->
<div id="addUserModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Tambah User Baru</h3>
        </div>
        <form action="{{ route('manageusers.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Divisi <span class="text-red-500">*</span></label>
                    <select name="division_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih Divisi</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL EDIT USER ================= -->
<div id="editUserModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Edit User</h3>
        </div>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="edit_email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                    <select name="division_id" id="edit_division_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Pilih Divisi</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL DELETE ================= -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
        <div class="p-6">
            <div class="text-center mb-4">
                <svg class="w-16 h-16 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-lg font-semibold mt-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mt-2">Apakah Anda yakin ingin menghapus user <span id="deleteUserName" class="font-semibold"></span>?</p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL IMPORT ================= -->
<div id="importModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Import Data Users</h3>
        </div>
        <form action="{{ route('manageusers.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-blue-700 font-medium mb-2">Format Excel yang diharapkan:</p>
                    <p class="text-xs text-blue-600">Kolom: <span class="font-mono">nama</span>, <span class="font-mono">email</span>, <span class="font-mono">divisi</span>, <span class="font-mono">password</span></p>
                    <p class="text-xs text-blue-600 mt-1">Password minimal 6 karakter</p>
                </div>

                <!-- File Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel <span class="text-red-500">*</span></label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full border rounded-lg p-2">
                    <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, .csv (Max: 2MB)</p>
                </div>

                <!-- ================= EXPORT INFO ================= -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span class="text-sm font-medium">Ingin mengekspor data?</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Klik tombol <span class="font-semibold text-blue-600">"Export Excel"</span> di bawah untuk mendownload semua data user yang ada.
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('manageusers.export', request()->query()) }}" 
                           class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export Excel
                        </a>
                    </div>
                </div>
                <!-- ========================================== -->
            </div>

            <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Import Data</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.getElementById('addUserModal').classList.add('flex');
}
function closeAddModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    document.getElementById('addUserModal').classList.remove('flex');
}
function openEditModal(userId, userData) {
    const form = document.getElementById('editUserForm');
    form.action = '/manageusers/' + userId;
    document.getElementById('edit_name').value = userData.name;
    document.getElementById('edit_email').value = userData.email;
    document.getElementById('edit_division_id').value = userData.division_id;
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserModal').classList.add('flex');
}
function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
    document.getElementById('editUserModal').classList.remove('flex');
}
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    document.getElementById('importModal').classList.add('flex');
}
function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importModal').classList.remove('flex');
}
function openDeleteModal(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = '/manageusers/' + userId;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
window.onclick = function(event) {
    if (event.target == document.getElementById('addUserModal')) closeAddModal();
    if (event.target == document.getElementById('editUserModal')) closeEditModal();
    if (event.target == document.getElementById('importModal')) closeImportModal();
    if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
}
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddModal();
        closeEditModal();
        closeImportModal();
        closeDeleteModal();
    }
});
</script>
@endsection
