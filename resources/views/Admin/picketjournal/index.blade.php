@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-[#004643]">
            Picket Journal Management
        </h1>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <!-- SEARCH -->
        <div class="flex justify-between mb-6">
            <input type="text"
                placeholder="Filter details... (cari nama, kelas, atau absen)"
                class="border rounded-full px-4 py-2 w-1/2">

            <div class="flex gap-3">
                <select class="border px-3 py-2 rounded">
                    <option>Divisi</option>
                </select>

                <button class="bg-yellow-400 px-4 py-2 rounded">
                    Unduh Excel
                </button>
            </div>
        </div>

        <h2 class="text-lg font-semibold mb-4">
            Management Picket Journal
        </h2>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Class</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3 text-center">Edit</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($journals as $journal)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                {{ $journal->user->name ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $loop->iteration }}
                            </td>

                            <td class="p-3">
                                12 RPL
                            </td>

                            <td class="p-3">
                                {{ $journal->activity }}
                            </td>

                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="#" class="text-blue-500">
                                        ✏
                                    </a>

                                    <form action="{{ route('admin.picketjournal.destroy', $journal->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <td class="p-3 text-center">
                                @if($journal->status == 'hadir')
                                    <span class="bg-green-200 text-green-700 px-3 py-1 rounded">
                                        Done
                                    </span>
                                @else
                                    <span class="bg-red-200 text-red-600 px-3 py-1 rounded">
                                        Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-6 text-gray-400">
                                No Data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection