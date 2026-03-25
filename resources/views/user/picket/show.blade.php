@extends('layouts.app')

@section('title', 'Detail Piket')

@section('content')
<div class="container mx-auto p-6">

    <div class="bg-white rounded-lg shadow p-6">

        <h2 class="text-xl font-bold mb-4">Detail Piket</h2>

        <!-- INFO UTAMA -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-500 text-sm">Activity</p>
                <p class="font-semibold">{{ $picket->activity }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Tanggal</p>
                <p class="font-semibold">{{ $picket->formatted_date }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-sm
                    @if($picket->status == 'Done') bg-green-100 text-green-700
                    @elseif($picket->status == 'Pending') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700
                    @endif">
                    {{ $picket->status }}
                </span>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Lokasi</p>
                <p class="font-semibold">{{ $picket->location ?? '-' }}</p>
            </div>
        </div>

        <!-- WAKTU -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-500 text-sm">Mulai</p>
                <p class="font-semibold">
                    {{ $picket->start_time ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Selesai</p>
                <p class="font-semibold">
                    {{ $picket->end_time ?? '-' }}
                </p>
            </div>
        </div>

        <!-- DESKRIPSI -->
        <div class="mb-6">
            <p class="text-gray-500 text-sm">Deskripsi</p>
            <p class="bg-gray-50 p-3 rounded">
                {{ $picket->description ?? 'Tidak ada deskripsi' }}
            </p>
        </div>

        <!-- FOTO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            <!-- BEFORE -->
            <div>
                <p class="text-gray-500 text-sm mb-2">Before</p>
                @if($picket->hasBeforePhoto())
                    <img src="{{ $picket->before_photo_url }}" class="rounded-lg shadow w-full">
                @else
                    <div class="bg-gray-100 p-6 text-center rounded">Tidak ada foto</div>
                @endif
            </div>

            <!-- AFTER -->
            <div>
                <p class="text-gray-500 text-sm mb-2">After</p>
                @if($picket->hasAfterPhoto())
                    <img src="{{ $picket->after_photo_url }}" class="rounded-lg shadow w-full">
                @else
                    <div class="bg-gray-100 p-6 text-center rounded">Tidak ada foto</div>
                @endif
            </div>

        </div>

        <!-- ACTION BUTTON -->
        <div class="flex gap-3">

            <a href="{{ route('user.picket.index') }}" 
               class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Kembali
            </a>

            @if(!$picket->start_time)
                <form action="{{ route('user.picket.start', $picket->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Mulai Piket
                    </button>
                </form>
            @elseif(!$picket->end_time)
                <form action="{{ route('user.picket.end', $picket->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Selesai Piket
                    </button>
                </form>
            @endif

        </div>

    </div>

</div>
@endsection