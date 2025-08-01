<?php

namespace App\Exports;

use App\Models\Topic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TopikExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periodeId;

    public function __construct($periodeId)
    {
        $this->periodeId = $periodeId;
    }

    /**
     * Ambil semua data topik untuk periode tertentu + count relasi proposal
     */
    public function collection()
    {
        return Topic::with('dosen')
            ->withCount([
                'proposalSubmission',
                'proposalSubmission as validated_submission_count' => function ($query) {
                    $query->where('status_pengajuan', 'Diterima');
                },
            ])
            ->where('period_id', $this->periodeId)
            ->get();
    }

    /**
     * Header kolom di file Excel
     */
    public function headings(): array
    {
        return [
            'Nama Dosen',
            'Status Dosen',
            'Topik Tugas Akhir',
            'Kelompok Keahlian',
            'Kuota Topik',
            'Jumlah Pengajuan',
            'Jumlah Validasi',
        ];
    }

    /**
     * Mapping setiap baris data ke kolom Excel
     */
    public function map($item): array
    {
        return [
            optional($item->dosen)->name,
            optional($item->dosen)->tipe_dos,
            $item->title,
            $item->focus,
            $item->kuota_topik,
            $item->proposal_submission_count,
            $item->validated_submission_count,
        ];
    }
}
