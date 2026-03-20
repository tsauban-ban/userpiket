<?php
// app/Exports/PicketJournalExport.php

namespace App\Exports;

use App\Models\PicketJournal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class PicketJournalExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PicketJournal::with('user.division')
            ->when($this->request->search, function ($query) {
                $query->where(function($q) {
                    $q->where('activity', 'like', "%{$this->request->search}%")
                      ->orWhere('description', 'like', "%{$this->request->search}%")
                      ->orWhereHas('user', function($userQuery) {
                          $userQuery->where('name', 'like', "%{$this->request->search}%");
                      });
                });
            })
            ->when($this->request->day, function ($query) {
                $query->whereRaw('DAYNAME(date) = ?', [$this->request->day]);
            })
            ->when($this->request->status, function ($query) {
                $query->where('status', $this->request->status);
            })
            ->when($this->request->date, function ($query) {
                $query->whereDate('date', $this->request->date);
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama User',
            'Divisi',
            'Tanggal',
            'Hari',
            'Activity',
            'Deskripsi',
            'Lokasi',
            'Status',
            'Jam Mulai',
            'Jam Selesai',
            'Catatan',
            'Dibuat',
            'Diperbarui'
        ];
    }

    public function map($journal): array
    {
        return [
            'PKT-' . str_pad($journal->id, 7, '0', STR_PAD_LEFT),
            $journal->user->name ?? '-',
            $journal->user->division->division_name ?? '-',
            $journal->date->format('d/m/Y'),
            $journal->date->locale('id')->isoFormat('dddd'),
            $journal->activity,
            $journal->description ?? '-',
            $journal->location ?? '-',
            $journal->status,
            $journal->start_time ? Carbon::parse($journal->start_time)->format('H:i') : '-',
            $journal->end_time ? Carbon::parse($journal->end_time)->format('H:i') : '-',
            $journal->notes ?? '-',
            $journal->created_at->format('d/m/Y H:i'),
            $journal->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}