
git push origin kinan/divisi-picketjournal-notification-ADMIN
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Piket</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #004643;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header-info {
            margin-bottom: 20px;
            font-size: 10px;
            color: #666;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-done { background-color: #d1fae5; color: #059669; }
        .status-approved { background-color: #dbeafe; color: #2563eb; }
        .status-rejected { background-color: #fee2e2; color: #dc2626; }
        .status-Hadir { background-color: #d1fae5; color: #059669; }
        .status-Sakit { background-color: #dbeafe; color: #2563eb; }
        .status-Izin { background-color: #fef3c7; color: #d97706; }
        .status-Alpha { background-color: #fee2e2; color: #dc2626; }
        .status-Terlambat { background-color: #fed7aa; color: #c2410c; }
    </style>
</head>
<body>
    <h1>LAPORAN PICKET JOURNAL</h1>
    <div class="subtitle">Aplikasi Piket</div>
    
    <div class="header-info">
        <div>Tanggal Export: {{ $export_date }}</div>
        <div>Total Data: {{ $total }} jadwal</div>
        @if($filters['search'])
            <div>Filter Pencarian: {{ $filters['search'] }}</div>
        @endif
        @if($filters['day'])
            <div>Filter Hari: {{ $filters['day'] }}</div>
        @endif
        @if($filters['status'])
            <div>Filter Status: {{ $filters['status'] }}</div>
        @endif
    </div>

    <table>
        <thead>
             <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Divisi</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Activity</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Jam</th>
             </tr>
        </thead>
        <tbody>
            @foreach($journals as $index => $journal)
             <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $journal->user->name }}</td>
                <td>{{ $journal->user->division->division_name ?? '-' }}</td>
                <td>{{ $journal->date->format('d/m/Y') }}</td>
                <td>{{ $journal->date->locale('id')->isoFormat('dddd') }}</td>
                <td>{{ $journal->activity }}</td>
                <td>{{ Str::limit($journal->description ?? '-', 50) }}</td>
                <td>
                    <span class="status-badge status-{{ $journal->status }}">
                        {{ ucfirst($journal->status) }}
                    </span>
                </td>
                <td>
                    {{ $journal->start_time ? \Carbon\Carbon::parse($journal->start_time)->format('H:i') : '07:00' }} - 
                    {{ $journal->end_time ? \Carbon\Carbon::parse($journal->end_time)->format('H:i') : '08:00' }}
                </td>
             </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $export_date }} | Aplikasi Piket
    </div>
</body>
</html>