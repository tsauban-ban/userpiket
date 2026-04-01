@extends('layouts.app')

@section('title', 'Detail Piket')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Detail Piket</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-500 text-sm">Activity</p>
                <p class="font-semibold text-lg">{{ $picket->activity }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tanggal</p>
                <p class="font-semibold">{{ $picket->formatted_date }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($picket->status == 'Done') bg-green-100 text-green-700
                    @elseif($picket->status == 'OnGoing') bg-blue-100 text-blue-700
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                <p class="text-blue-600 text-xs font-bold uppercase mb-1">Waktu Mulai</p>
                <p class="font-mono text-xl font-semibold">{{ $picket->start_time ?? '--:--:--' }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Waktu Selesai</p>
                <p class="font-mono text-xl font-semibold">{{ $picket->end_time ?? '--:--:--' }}</p>
                <!-- @if($picket->status == 'Done')
                    <p class="text-xs text-gray-400 mt-1 italic">*Waktu saat tombol selesai ditekan</p>
                @endif -->
            </div>
        </div>

        <div class="mb-6">
            <p class="text-gray-500 text-sm mb-1">Deskripsi</p>
            <p class="bg-gray-50 p-3 rounded border italic text-gray-700">
                {{ $picket->description ?? 'Tidak ada deskripsi' }}
            </p>
        </div>

        @if($picket->status == 'Done')
        <div class="border-t pt-6 mb-6">
            <h3 class="font-bold text-gray-800 mb-4">Bukti Foto Piket</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Foto Before</p>
                    <div class="rounded-lg overflow-hidden border bg-gray-100 shadow-sm">
                        @if($picket->before_photo)
                            @php
                                // Cek apakah di dalam string ada kata 'picket_photos' (cara baru)
                                $isNewPath = Str::contains($picket->before_photo, 'picket_photos');
                                $srcBefore = $isNewPath ? asset('storage/' . $picket->before_photo) : asset('images/' . $picket->before_photo);
                            @endphp
                            <img src="{{ $srcBefore }}" class="w-full h-64 object-cover" onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ditemukan';">
                        @else
                            <div class="h-64 flex items-center justify-center text-gray-400 italic">Belum ada foto</div>
                        @endif
                    </div>
                </div>
                            
                <div>
                    <p class="text-sm text-gray-500 mb-2">Foto After</p>
                    <div class="rounded-lg overflow-hidden border bg-gray-100 shadow-sm">
                        @if($picket->after_photo)
                            @php
                                $isNewPathAfter = Str::contains($picket->after_photo, 'picket_photos');
                                $srcAfter = $isNewPathAfter ? asset('storage/' . $picket->after_photo) : asset('images/' . $picket->after_photo);
                            @endphp
                            <img src="{{ $srcAfter }}" class="w-full h-64 object-cover" onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ditemukan';">
                        @else
                            <div class="h-64 flex items-center justify-center text-gray-400 italic">Belum ada foto</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="border-t pt-6">
            @if($picket->status == 'OnGoing')
                <form action="{{ route('user.picket.end', $picket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1 font-bold">Upload Foto Before <span class="text-red-500">*</span></label>
                            <input type="file" name="before_photo" required class="text-sm border p-2 rounded bg-gray-50 w-full focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1 font-bold">Upload Foto After <span class="text-red-500">*</span></label>
                            <input type="file" name="after_photo" required class="text-sm border p-2 rounded bg-gray-50 w-full focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.picket.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Kembali</a>
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-md font-semibold transition">
                            Selesai Piket
                        </button>
                    </div>
                </form>
            @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.picket.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Kembali</a>
                    @if($picket->status == 'Pending')
                        <form action="{{ route('user.picket.start', $picket->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md font-semibold transition">
                                Mulai Piket
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Ambil tanggal murni database (YYYY-MM-DD) agar tidak terjadi NaN
    let dateStr = "{{ $picket->getRawOriginal('date') ?? $picket->date }}"; 
    let startTime = "{{ $picket->start_time }}";
    let endTime = "{{ $picket->end_time }}";

    function parseDateTime(d, t) {
        if (!d || !t || t === '--:--:--' || t === '--:--') return null;
        // Gabungkan tanggal dan waktu dengan spasi agar valid untuk Date Object
        return new Date(d + " " + t);
    }

    let start = parseDateTime(dateStr, startTime);
    let end = parseDateTime(dateStr, endTime);

    function updateStopwatch() {
        let stopwatchEl = document.getElementById("stopwatch");
        if (!stopwatchEl || !start || isNaN(start.getTime())) return;

        // Jika sudah ada end_time, gunakan selisih start-end, jika belum gunakan waktu sekarang
        let now = (end && !isNaN(end.getTime())) ? end : new Date();
        let diff = now - start;
        
        if (diff < 0) diff = 0;

        let hours = Math.floor(diff / 3600000);
        let minutes = Math.floor((diff % 3600000) / 60000);
        let seconds = Math.floor((diff % 60000) / 1000);

        stopwatchEl.innerHTML = 
            String(hours).padStart(2, '0') + ":" + 
            String(minutes).padStart(2, '0') + ":" + 
            String(seconds).padStart(2, '0');
    }

    // Jalankan stopwatch jika elemen tersedia (hanya saat status OnGoing)
    if (document.getElementById("stopwatch")) {
        updateStopwatch();
        // Update setiap detik jika belum selesai
        if (!end || isNaN(end.getTime())) {
            setInterval(updateStopwatch, 1000);
        }
    }
</script>
@endsection