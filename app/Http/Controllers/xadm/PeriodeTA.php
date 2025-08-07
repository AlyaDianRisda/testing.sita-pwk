<?php
namespace App\Http\Controllers\xadm;

use App\Http\Controllers\Controller;
use App\Models\ArchivedPeriod;
use App\Models\Period;
use App\Models\ProposalKuotaDosen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PeriodeTA extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x0ptaView(): View
    {
        return view('xadm.PTA');
    }

    // GET::DATA1
    public function x0ptaData1(Request $request)
    {
        if ($request->ajax()) {
            $query = Period::query();

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();
            $query->orderByDesc('is_open');

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = Period::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get(['id', 'name', 'type', 'start_date', 'end_date', 'is_open', 'created_at'])
                ->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'name'       => $item->name,
                        'type'       => $item->type,
                        'start_date' => $item->start_date,
                        'end_date'   => $item->end_date,
                        'is_open'    => $item->is_open
                        ? '<div class="d-flex"><span class="badge-status badge-open flex-fill">Dibuka</span></div>'
                        : '<div class="d-flex"><span class="badge-status badge-closed flex-fill">Ditutup</span></div>',
                        'aksi'       => $item->is_open
                        ? '<div class="d-flex"><button class="btn btn-primary btn-sm flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" onclick="if(confirm(\'Yakin ingin tutup periode ini?\')) tutupPeriode(' . $item->id . ')">Tutup</button>'
                        : '<div class="d-flex"><button class="btn btn-secondary btn-sm flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" onclick="if(confirm(\'Yakin ingin hapus data ini?\')) hapusPeriode(' . $item->id . ')">Hapus</button>',
                        'created_at' => $item->created_at->toDateTimeString(),
                    ];
                });

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }

        abort(404);
    }

    // GET::DATA2
    public function x0ptaData2(Request $request)
    {
        if ($request->ajax()) {
            $query = ProposalKuotaDosen::with(['period', 'dosen'])->whereHas('period', function ($q) {
                $q->where('is_open', true);
            });

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->whereHas('dosen', function ($q) use ($search) {
                    $q->where('tipe_dos', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = ProposalKuotaDosen::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'dosen'      => optional($item->dosen)->name,
                        'tipe_dosen' => optional($item->dosen)->tipe_dos,
                        'kuota_ta'   => $item->kuota_total,
                        'aksi'       => '<div class="d-flex"><button class="btn btn-primary btn-sm flex-fill edit-kuota" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" data-id="' . $item->id . '" data-kuota="' . $item->kuota_total . '">Edit Kuota</button>',
                        'title'      => optional($item->period)->name,
                        'created_at' => $item->created_at->toDateTimeString(),
                    ];
                });

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }
    }

    // POST::NEW_PERIODE
    public function x0ptaNewPeriode(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'title.required'          => 'Title wajib diisi.',
            'type.required'           => 'Jenis periode wajib dipilih.',
            'start_date.required'     => 'Tanggal mulai wajib diisi.',
            'end_date.required'       => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $type = $request->input('type');

        $adaYangMasihBuka = Period::where('type', $type)
            ->where('is_open', true)
            ->exists();

        if ($adaYangMasihBuka) {
            return response()->json([
                'message' => 'Ada jenis periode yang sama masih dibuka. Tutup dulu sebelum membuat yang baru.',
            ], 422);
        }

        $periode = Period::create([
            'name'       => $request->title,
            'type'       => $request->type,
            'start_date' => Carbon::parse($request->start_date)->startOfDay(),
            'end_date'   => Carbon::parse($request->end_date)->endOfDay(),
            'is_open'    => true,
        ]);

        if ($request->type === 'Pengajuan Proposal') {
            $this->x0ptaCreateKuota($periode->id);
        }

        return response()->json(['message' => 'Periode berhasil dibuat.']);
    }

    // CRT::CREATE_KUOTA
    public function x0ptaCreateKuota($period_id)
    {
        $dosenList = User::where('type', '2')->get();

        foreach ($dosenList as $dosen) {
            $kuota_total = 2;

            if ($dosen->tipe_dos === 'Utama') {
                $kuota_total = 2;
            } elseif ($dosen->tipe_dos === 'Pendamping') {
                $kuota_total = 0;
            }

            ProposalKuotaDosen::updateOrCreate(
                [
                    'period_id' => $period_id,
                    'dosen_id'  => $dosen->id,
                ],
                [
                    'kuota_total'   => $kuota_total,
                    'kuota_tersisa' => $kuota_total,
                ]
            );
        }
    }

    // UPD::EDIT_KUOTA
    public function x0ptaEditKuota(Request $request, $id)
    {
        $validated = $request->validate([
            'kuota_ta' => 'required|integer|min:0',
        ]);

        $kuota = ProposalKuotaDosen::findOrFail($id);

        // Hitung kuota terpakai = total - tersisa
        $kuotaTerpakai = $kuota->kuota_total - $kuota->kuota_tersisa;

        // Validasi kuota baru tidak boleh kurang dari kuota terpakai
        if ($validated['kuota_ta'] < $kuotaTerpakai) {
            return response()->json([
                'message' => "Gagal: Kuota tidak boleh lebih kecil dari jumlah yang sudah digunakan ($kuotaTerpakai mahasiswa).",
            ], 422);
        }

        // Update kuota total
        $kuota->kuota_total = $validated['kuota_ta'];

        // Update kuota tersisa = total baru - terpakai (agar sisa sesuai)
        $kuota->kuota_tersisa = $validated['kuota_ta'] - $kuotaTerpakai;

        $kuota->save();

        return response()->json(['message' => 'Kuota berhasil diperbarui']);
    }

    // POST::CLOSE_PERIODE
    public function x0ptaClosePeriode($id)
    {
        $periode = Period::findOrFail($id);
        if (! $periode->is_open) {
            return response()->json(['message' => 'Periode sudah ditutup.'], 400);
        }

        $periode->is_open = false;
        $periode->save();

        $timestamp     = Carbon::now()->format('Ymd_His');
        $folder        = 'arsip/';
        $exportSuccess = false;

        $filePathProposal = $folder . 'arsip_proposal_' . $timestamp . '.xlsx';
        $filePathTopik    = $folder . 'arsip_topik_' . $timestamp . '.xlsx';

        try {
            switch ($periode->type) {
                case 'Pengajuan Proposal':
                    $proposalCount = \App\Models\ProposalSubmission::whereHas('topik', function ($q) use ($periode) {
                        $q->where('period_id', $periode->id);
                    })->count();

                    $topikCount = \App\Models\Topic::where('period_id', $periode->id)->count();

                    if ($proposalCount > 0) {
                        Excel::store(new \App\Exports\ProposalExport($periode->id), $filePathProposal);
                        $exportSuccess = true;
                    }

                    if ($topikCount > 0) {
                        Excel::store(new \App\Exports\TopikExport($periode->id), $filePathTopik);
                        $exportSuccess = true;
                    }

                    break;

                case 'Seminar Proposal':
                case 'Seminar Pembahasan':
                case 'Sidang Ujian':
                    $sidangCount = \App\Models\SidangSubmission::where('periode_id', $periode->id)->count();
                    if ($sidangCount > 0) {
                        $filePathSidang = $folder . 'arsip_sidang_' . strtolower(str_replace(' ', '_', $periode->type)) . '_' . $timestamp . '.xlsx';
                        Excel::store(new \App\Exports\SidangExport($periode->id, $periode->type), $filePathSidang);
                        $filePathProposal = $filePathSidang; // agar tetap terekam salah satu di database
                        $exportSuccess    = true;
                    }
                    break;
            }
        } catch (\Throwable $e) {
            Log::error("Gagal export periode [{$periode->id}]: " . $e->getMessage());
        }

        ArchivedPeriod::create([
            'name'        => $periode->name,
            'type'        => $periode->type,
            'start_date'  => $periode->start_date,
            'end_date'    => $periode->end_date,
            'archived_at' => Carbon::now(),
            'file_path'   => $exportSuccess ? $filePathProposal : null,
            'file_path2'  => $exportSuccess ? $filePathTopik : null,
        ]);

        return response()->json([
            'message' => $exportSuccess
            ? 'Periode berhasil ditutup dan diarsipkan.'
            : 'Periode ditutup. Tidak ada data untuk diarsipkan.',
        ]);
    }

    // DEL::DELETE_PERIODE
    public function x0ptaDeletePeriode($id)
    {
        $period = Period::findOrFail($id);
        $period->delete();

        return response()->json([
            'message' => 'Periode dan semua topik terkait berhasil dihapus.',
        ]);
    }

}
