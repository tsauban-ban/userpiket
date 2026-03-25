@extends('user.dashboard')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Piket Saya</h2>

        <a href="{{ route('user.picket.create') }}"
           class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
            + Tambah Piket
        </a>
    </div>

    <!-- CARD TABLE -->
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Activity</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($data as $item)
                <tr class="hover:bg-orange-50 transition">

                    <!-- TANGGAL -->
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                    </td>

                    <!-- ACTIVITY -->
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $item->activity }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-3">
                        @if($item->status == 'Done')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                Done
                            </span>
                        @elseif($item->status == 'Pending')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                                Pending
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                                {{ $item->status }}
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-3 flex gap-2 flex-wrap">

                        <!-- DETAIL -->
                        <a href="{{ route('user.picket.show', $item->id) }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                            Detail
                        </a>

                        <!-- MULAI -->
                       @if($item->status == 'Pending')
                            <form action="{{ route('user.picket.start', $item->id) }}" method="POST">
                                @csrf
                                <button class="bg-green-500 text-white px-3 py-1 rounded text-sm">
                                    Mulai
                                </button>
                            </form>
                        @endif

                        @if($item->status == 'On Progress')
                            <form action="{{ route('user.picket.end', $item->id) }}" method="POST">
                                @csrf
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-sm">
                                    Selesai
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">
                        Belum ada data piket
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection