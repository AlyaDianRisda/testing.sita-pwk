<?php
namespace App\Exports;

use App\Models\SidangSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SidangExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periodId;

    public function __construct($periodId)
    {
        $this->periodId = $periodId;
    }

    public function collection()
    {
        return SidangSubmission::with(['user', 'topik', 'dosen', 'penguji', 'penguji2'])
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->user?->name ?? 'N/A',
            $row->user?->nim ?? 'NIM',
            $row->judul,
            $row->topik?->nama_topik ?? 'N/A',
            $row->dosen?->name ?? 'N/A',
            $row->penguji?->name ?? '-',
            $row->penguji2?->name ?? '-',
            $row->jadwal_sidang ?? '-',
            $row->waktu_sidang ?? '-',
            $row->lokasi_sidang ?? '-',
            $row->skema_sidang ?? '-',
            $row->link_sidang ?? '-',
            $row->tipe_sidang,
            $row->status_sidang,
            $row->hasil ?? '-',
            $row->created_at->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'NIM',
            'Judul',
            'Topik',
            'Dosen Utama',
            'Penguji 1',
            'Penguji 2',
            'Tanggal Sidang',
            'Waktu Sidang',
            'Lokasi',
            'Skema',
            'Link',
            'Tipe Sidang',
            'Status',
            'Hasil',
            'Tanggal Pendaftaran',
        ];
    }
}
