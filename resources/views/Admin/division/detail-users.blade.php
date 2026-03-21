@extends('layouts.app')

@section('title', 'Detail Divisi')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">{{ $division->division_name }}</h2>
            <p class="text-gray-600 mt-1">Daftar User di Divisi Ini</p>
        </div>
        <a href="{{ route('divisions.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            ← Kembali
        </a>
    </div>

    
    
    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-3xl font-bold text-teal-600">{{ $division->users->count() }}</div>
                <div class="text-sm text-gray-600">Total Users</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-teal-600">
                    {{ $division->users->where('role', 'user')->count() }}
                </div>
                <div class="text-sm text-gray-600">Regular Users</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-teal-600">
                    {{ $division->users->where('role', 'admin')->count() }}
                </div>
                <div class="text-sm text-gray-600">Admin</div>
            </div>
        </div>
    </div>

    
    
    <div class="overflow-x-auto">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-3 text-left">No</th>
                    <th class="border p-3 text-left">Nama User</th>
                    <th class="border p-3 text-left">Email</th>
                    <th class="border p-3 text-left">Role</th>
                    <th class="border p-3 text-left">Jumlah Piket</th>
                </tr>
            </thead>
            <tbody>
                @forelse($division->users as $index => $user)
                <tr>
                    <td class="border p-3">{{ $index + 1 }}</td>
                    <td class="border p-3 font-medium">{{ $user->name }}</td>
                    <td class="border p-3">{{ $user->email }}</td>
                    <td class="border p-3">
                        <span class="px-2 py-1 rounded text-sm
                            @if($user->role == 'admin') bg-purple-100 text-purple-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="border p-3">
                        {{ $user->picketJournals->count() }} jurnal
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border p-8 text-center text-gray-500">
                        <div class="text-6xl mb-4">👥</div>
                        <p class="text-lg">Belum ada user di divisi ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection