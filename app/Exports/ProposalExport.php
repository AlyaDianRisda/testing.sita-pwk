<?php
namespace App\Exports;

use App\Models\ProposalSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProposalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periodId;

    public function __construct($periodId)
    {
        $this->periodId = $periodId;
    }

    public function collection()
    {
        return ProposalSubmission::with(['user', 'topik', 'dos_utama', 'dos_kedua'])
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->user?->name ?? 'N/A',
            $row->user?->nim ?? 'N/A',
            $row->topik?->title ?? 'N/A',
            $row->rancangan_judul,
            $row->dos_utama?->name ?? 'N/A',
            $row->dos_kedua?->name ?? '-',
            $row->draft_file_path,
            $row->catatan_validasi ?? '-',
            $row->status_pengajuan,
            $row->created_at->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'NIM',
            'Topik',
            'Rancangan Judul',
            'Dosen Utama',
            'Dosen Kedua',
            'Draft File',
            'Catatan Validasi',
            'Status Pengajuan',
            'Tanggal Pengajuan',
        ];
    }
}
