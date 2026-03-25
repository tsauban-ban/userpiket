@extends('user.dashboard')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Tambah Piket</h2>
        <p class="text-gray-500 text-sm">Isi data kegiatan piket kamu</p>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('user.picket.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ACTIVITY -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activity</label>
                    <input type="text" name="activity" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- TANGGAL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- LOKASI -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- STATUS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500">
                        <option value="Pending">Pending</option>
                        <option value="Done">Done</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Alpha">Alpha</option>
                        <option value="Terlambat">Terlambat</option>
                    </select>
                </div>

            </div>

            <!-- DESKRIPSI -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500"></textarea>
            </div>

            <!-- FOTO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                <!-- BEFORE -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Before</label>
                    <input type="file" name="before_photo"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- AFTER -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto After</label>
                    <input type="file" name="after_photo"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-6 flex gap-3">

                <a href="{{ route('user.picket.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Kembali
                </a>

                <button type="submit"
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection