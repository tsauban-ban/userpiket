@extends('layouts.app')

@section('title', 'Picket Schedule Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#004643]">Picket Schedule Management</h1>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                {{ $schedules->count() }} jadwal
            </span>
            
            <button onclick="openScheduleModal()" 
               class="bg-[#F9BC60] text-[#004643] px-4 py-2 rounded-lg hover:bg-[#e5a94d] flex items-center gap-2 transition duration-200">
                <span class="material-symbols-outlined text-base">add</span>
                Tambah Jadwal
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.picket-schedule.index') }}" class="space-y-4">
            <div class="flex gap-4 flex-wrap">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama user..." 
                       class="flex-1 border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent">
                
                <input type="date" 
                       name="date" 
                       value="{{ request('date') }}"
                       class="border rounded-lg p-2.5 min-w-[180px] bg-white focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent">
                
                <select name="day" class="border rounded-lg p-2.5 min-w-[150px] bg-white">
                    <option value="">SEMUA HARI</option>
                    <option value="Monday" {{ request('day') == 'Monday' ? 'selected' : '' }}>Senin</option>
                    <option value="Tuesday" {{ request('day') == 'Tuesday' ? 'selected' : '' }}>Selasa</option>
                    <option value="Wednesday" {{ request('day') == 'Wednesday' ? 'selected' : '' }}>Rabu</option>
                    <option value="Thursday" {{ request('day') == 'Thursday' ? 'selected' : '' }}>Kamis</option>
                    <option value="Friday" {{ request('day') == 'Friday' ? 'selected' : '' }}>Jumat</option>
                    <option value="Saturday" {{ request('day') == 'Saturday' ? 'selected' : '' }}>Sabtu</option>
                    <option value="Sunday" {{ request('day') == 'Sunday' ? 'selected' : '' }}>Minggu</option>
                </select>

                <button type="submit" class="bg-[#F9BC60] text-[#004643] px-6 py-2.5 rounded-lg hover:bg-[#e5a94d] font-medium">
                    Filter
                </button>
            </div>

            @if(request()->anyFilled(['search', 'date', 'day']))
            <div class="mt-2">
                <a href="{{ route('admin.picket-schedule.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">close</span>
                    Reset semua filter
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border-b p-3 text-left font-semibold text-gray-700">No</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Nama User</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Tanggal</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Hari</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Lokasi</th>
                    <th class="border-b p-3 text-left font-semibold text-gray-700">Catatan</th>
                    <th class="border-b p-3 text-center font-semibold text-gray-700">Aksi</th>
                  </tr>
            </thead>
            <tbody>
                @forelse($schedules as $index => $schedule)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="p-3 text-gray-600">{{ $index + 1 }}</td>
                    <td class="p-3 font-medium text-gray-800">
                        {{ $schedule->user->name }}
                        @if($schedule->user->division)
                            <span class="text-xs text-gray-500 block">{{ $schedule->user->division->division_name ?? '' }}</span>
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">
                        {{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}
                    </td>
                    <td class="p-3">
                        @switch($schedule->day)
                            @case('Monday') <span class="text-gray-700">Senin</span> @break
                            @case('Tuesday') <span class="text-gray-700">Selasa</span> @break
                            @case('Wednesday') <span class="text-gray-700">Rabu</span> @break
                            @case('Thursday') <span class="text-gray-700">Kamis</span> @break
                            @case('Friday') <span class="text-gray-700">Jumat</span> @break
                            @case('Saturday') <span class="text-gray-700">Sabtu</span> @break
                            @case('Sunday') <span class="text-gray-700">Minggu</span> @break
                            @default {{ $schedule->day }}
                        @endswitch
                    </td>
                    <td class="p-3">
                        @if($schedule->location)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                {{ $schedule->location }}
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="p-3">
                        @if($schedule->notes)
                            <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $schedule->notes }}">
                                {{ Str::limit($schedule->notes, 50) }}
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="editSchedule({{ $schedule->id }})" 
                                class="text-[#F9BC60] hover:text-[#e5a94d] inline-block transition mr-2" 
                                title="Edit">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </button>
                        <button onclick="deleteSchedule({{ $schedule->id }})" 
                                class="text-red-500 hover:text-red-700 inline-block transition" 
                                title="Hapus">
                            <span class="material-symbols-outlined text-xl">delete</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl mb-3 text-gray-300">schedule</span>
                        <p class="text-lg font-medium">No Data</p>
                        <p class="text-sm mt-1">Belum ada jadwal piket</p>
                        <button onclick="openScheduleModal()" 
                                class="inline-block mt-4 bg-[#F9BC60] text-[#004643] px-4 py-2 rounded-lg hover:bg-[#e5a94d] transition">
                            + Tambah Jadwal Sekarang
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="scheduleModal" class="fixed inset-0 bg-[#b7dcd1] bg-opacity-80 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-bold text-[#004643]" id="modalTitle">Tambah Jadwal Piket</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form id="scheduleForm" class="p-6">
            @csrf
            <input type="hidden" name="id" id="scheduleId">
            
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama User <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required 
                            class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent">
                        <option value="">Pilih User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} {{ $user->division ? '(' . $user->division->division_name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-red-500 text-xs mt-1 hidden" id="user_id_error"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="date" required
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent"
                           onchange="autoFillDay()">
                    <div class="text-red-500 text-xs mt-1 hidden" id="date_error"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="day" id="day" required 
                            class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent bg-gray-50">
                        <option value="">Pilih Hari</option>
                        <option value="Monday">Senin</option>
                        <option value="Tuesday">Selasa</option>
                        <option value="Wednesday">Rabu</option>
                        <option value="Thursday">Kamis</option>
                        <option value="Friday">Jumat</option>
                        <option value="Saturday">Sabtu</option>
                        <option value="Sunday">Minggu</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">* Akan terisi otomatis saat memilih tanggal</p>
                    <div class="text-red-500 text-xs mt-1 hidden" id="day_error"></div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" id="location" 
                           class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent"
                           placeholder="Contoh: Ruang A, Lab Komputer">
                    <div class="text-red-500 text-xs mt-1 hidden" id="location_error"></div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-[#F9BC60] focus:border-transparent"
                              placeholder="Catatan tambahan..."></textarea>
                    <div class="text-red-500 text-xs mt-1 hidden" id="notes_error"></div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#F9BC60] text-[#004643] rounded-lg hover:bg-[#e5a94d] transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Function to auto-fill day based on selected date
function autoFillDay() {
    const dateInput = document.getElementById('date');
    const daySelect = document.getElementById('day');
    
    if (dateInput.value) {
        const selectedDate = new Date(dateInput.value);
        const dayNumber = selectedDate.getDay();
        
        const dayMap = {
            0: 'Sunday',
            1: 'Monday',
            2: 'Tuesday',
            3: 'Wednesday',
            4: 'Thursday',
            5: 'Friday',
            6: 'Saturday'
        };
        
        const dayName = dayMap[dayNumber];
        
        // Convert to Indonesian
        const dayMapIndo = {
            'Sunday': 'Minggu',
            'Monday': 'Senin',
            'Tuesday': 'Selasa',
            'Wednesday': 'Rabu',
            'Thursday': 'Kamis',
            'Friday': 'Jumat',
            'Saturday': 'Sabtu'
        };
        
        // Set value to English (for database)
        daySelect.value = dayName;
        
        // Visual feedback
        daySelect.style.backgroundColor = '#f0fdf4';
        setTimeout(() => {
            daySelect.style.backgroundColor = '';
        }, 500);
    }
}

function openScheduleModal() {
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.add('flex');
    document.getElementById('scheduleForm').reset();
    document.getElementById('scheduleId').value = '';
    document.getElementById('modalTitle').innerText = 'Tambah Jadwal Piket';
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').value = today;
    autoFillDay();
    
    clearErrors();
}

function closeModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleModal').classList.remove('flex');
    clearErrors();
}

function clearErrors() {
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
    });
    document.querySelectorAll('[id$="_error"]').forEach(el => {
        el.classList.add('hidden');
        el.innerText = '';
    });
}

function showErrors(errors) {
    for (let key in errors) {
        let input = document.getElementById(key);
        let errorDiv = document.getElementById(key + '_error');
        if (input && errorDiv) {
            input.classList.add('border-red-500');
            errorDiv.innerText = errors[key][0];
            errorDiv.classList.remove('hidden');
        }
    }
}

function editSchedule(id) {
    fetch(`/admin/picket-schedule/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('scheduleId').value = data.id;
            document.getElementById('user_id').value = data.user_id;
            document.getElementById('date').value = data.date;
            document.getElementById('day').value = data.day;
            document.getElementById('location').value = data.location || '';
            document.getElementById('notes').value = data.notes || '';
            document.getElementById('modalTitle').innerText = 'Edit Jadwal Piket';
            openScheduleModal();
        })
        .catch(error => {
            Swal.fire('Error!', 'Gagal mengambil data jadwal', 'error');
        });
}

function deleteSchedule(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data jadwal piket akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/picket-schedule/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Gagal!', 'Terjadi kesalahan server', 'error');
            });
        }
    });
}

document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let id = document.getElementById('scheduleId').value;
    let url = id ? `/admin/picket-schedule/${id}` : '/admin/picket-schedule';
    let method = id ? 'PUT' : 'POST';
    
    let formData = new FormData(this);
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Berhasil!', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else if (data.errors) {
            showErrors(data.errors);
        } else {
            Swal.fire('Gagal!', 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        Swal.fire('Gagal!', 'Terjadi kesalahan server', 'error');
    });
});

document.getElementById('scheduleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('date').addEventListener('change', autoFillDay);
</script>
@endsection