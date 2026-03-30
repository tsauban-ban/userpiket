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
                <p id="stopwatch" class="font-semibold text-lg text-blue-600">
                    00:00:00
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
            <!-- <div>
                <p class="text-gray-500 text-sm mb-2">Before</p>
                <form action="{{ route('user.picket.upload', $picket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="before_photo">
                    <button type="submit">Upload</button>
                </form>
            </div> -->

            <!-- AFTER -->
            <!-- <div>
                <form action="{{ route('user.picket.upload', $picket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="after_photo">
                    <button type="submit">Upload</button>
                </form>
            </div> -->

        </div>

        <!-- ACTION BUTTON -->
        <div class="border-t pt-6">
    <div class="flex flex-col md:flex-row gap-4">
        
        <form action="{{ route('user.picket.end', $picket->id) }}" method="POST" enctype="multipart/form-data" class="w-full">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">Foto Before</label>
                    <input type="file" name="before_photo" required class="text-sm border p-1 rounded bg-white w-full">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">Foto After</label>
                    <input type="file" name="after_photo" required class="text-sm border p-1 rounded bg-white w-full">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('user.picket.index') }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors inline-block">
                   Kembali
                </a>
                
                @if($picket->status == 'Pending')
                    @elseif($picket->status == 'OnGoing')
                    <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-sm">
                        Selesai Piket
                    </button>
                @endif
            </div>
        </form>

    </div>
</div>

<script>
    // Pastikan format date adalah YYYY-MM-DD dari database, bukan format 'Senin, 30 Maret'
    // Jika $picket->date sudah terformat di Model, gunakan kolom asli (misal: getAttributes()['date'])
    let dateStr = "{{ $picket->getAttributes()['date'] ?? $picket->date }}"; 
    let startTime = "{{ $picket->start_time }}";
    let endTime = "{{ $picket->end_time }}";

    function parseDateTime(d, t) {
        if (!d || !t) return null;
        // Menggabungkan YYYY-MM-DD dan HH:mm:ss menjadi format ISO
        return new Date(d + "T" + t);
    }

    let start = parseDateTime(dateStr, startTime);
    let end = parseDateTime(dateStr, endTime);

    function updateStopwatch() {
        if (!start || isNaN(start.getTime())) {
            document.getElementById("stopwatch").innerHTML = "00:00:00";
            return;
        }

        let now = (end && !isNaN(end.getTime())) ? end : new Date();
        let diff = now - start;

        if (diff < 0) diff = 0;

        let hours = Math.floor(diff / 3600000);
        let minutes = Math.floor((diff % 3600000) / 60000);
        let seconds = Math.floor((diff % 60000) / 1000);

        document.getElementById("stopwatch").innerHTML =
            String(hours).padStart(2, '0') + ":" +
            String(minutes).padStart(2, '0') + ":" +
            String(seconds).padStart(2, '0');
    }

    updateStopwatch();

    // Jalankan interval hanya jika status masih OnGoing (belum ada end time)
    if (!end || isNaN(end.getTime())) {
        setInterval(updateStopwatch, 1000);
    }
</script>
@endsection