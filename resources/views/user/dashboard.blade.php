@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <div class="text-center mb-6">
        <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-4xl text-teal-600">person</span>
        </div>
        <h1 class="text-2xl font-bold text-[#004643]">Selamat Datang!</h1>
        <p class="text-gray-600 mt-1">{{ Auth::user()->name }}</p>
    </div>
    
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Akun Anda</h3>
        <table class="w-full">
            <tr class="border-b">
                <td class="py-2 text-gray-600 w-1/3">Nama Lengkap</td>
                <td class="py-2 font-medium">: {{ Auth::user()->name }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 text-gray-600">Email</td>
                <td class="py-2 font-medium">: {{ Auth::user()->email }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 text-gray-600">Divisi</td>
                <td class="py-2 font-medium">: {{ Auth::user()->division->division_name ?? 'Belum ada divisi' }}</td>
            </tr>
            <tr>
                <td class="py-2 text-gray-600">Role</td>
                <td class="py-2 font-medium">: {{ ucfirst(Auth::user()->role) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
        <p class="text-yellow-800 text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">info</span>
            Anda login sebagai user biasa. Halaman admin tidak dapat diakses.
        </p>
    </div>
</div>
@endsection