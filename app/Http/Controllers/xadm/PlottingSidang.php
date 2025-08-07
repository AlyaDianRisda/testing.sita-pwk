<?php
namespace App\Http\Controllers\xadm;

use App\Http\Controllers\Controller;
use App\Models\SidangSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

setlocale(LC_TIME, 'id_ID'); // Untuk fungsi bawaan PHP
Carbon::setLocale('id');     // Untuk Carbon

class PlottingSidang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x0psdView(): View
    {
        return view('xadm.PSD');
    }

    // GET::DATA1
    public function x0psdData1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen', 'penguji', 'penguji2'])
                ->whereNotIn('status_sidang', ['Ditolak', 'Selesai', 'Dinilai']);
            $recordsTotal = SidangSubmission::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('tipe_sidang', 'like', "%{$search}%")
                        ->orWhere('tipe_pengajuan', 'like', "%{$search}%")
                        ->orWhere('skema_sidang', 'like', "%{$search}%")
                        ->orWhere('status_sidang', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($subQ) use ($search) {
                            $subQ->where('name', 'like', "%{$search}%")
                                ->orWhere('nim', 'like', "%{$search}%");
                        });
                });
            }

            $filteredQuery   = clone $query;
            $recordsFiltered = $filteredQuery->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                if ($orderColumn === 'status') {
                    $query->orderByRaw("
                        CASE
                            WHEN status_sidang = 'Pending' THEN 0
                            WHEN status_sidang = 'Selesai' THEN 2
                            ELSE 1
                        END, status_sidang $orderDir
                    ");
                } else {
                    $validColumns = SidangSubmission::getTableColumns();
                    if (in_array($orderColumn, $validColumns)) {
                        $query->orderBy($orderColumn, $orderDir);
                    }
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'nim'            => optional($item->user)->nim,
                        'nama'           => optional($item->user)->name,
                        'tipe_sidang'    => $item->tipe_sidang,
                        'tipe_pengajuan' => $item->tipe_pengajuan,
                        'penguji'        => optional($item->penguji)->name ?? 'N/A',
                        'penguji2'       => optional($item->penguji2)->name ?? 'N/A',
                        'tanggal'        => Carbon::parse($item->jadwal_sidang)->translatedFormat('d M Y') ?? 'N/A',
                        'waktu'          => $item->waktu_sidang ?? 'N/A',
                        'lokasi'         => $item->lokasi_sidang ?? 'N/A',
                        'skema'          => $item->skema_sidang ?? 'N/A',
                        'status'         => $item->status_sidang,
                        'aksi'           => $item->id
                        ? '<div class="d-flex"><a href="' . route('x0.PlottingSidang-json2', ['id' => $item->id]) . '" class="btn btn-primary btn-sm flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;">Manage</a></div>'
                        : '',
                        'created_at'     => $item->created_at->toDateTimeString(),
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

    // PAGE::DATA2
    public function x0psdData2(Request $request)
    {
        $data = SidangSubmission::with(['topik', 'user', 'dosen', 'penguji', 'penguji2'])
            ->where('id', $request->id)
            ->first();

        $penguji  = User::where('type', '2')->get();
        $penguji2 = User::where('type', '2')->get();
        $form1    = null;
        $form2    = null;
        $form3    = null;
        $form4    = null;

        $formLabels = ['Form 1', 'Form 2', 'Form 3', 'Form 4'];

        if ($data?->tipe_sidang === 'Seminar Proposal') {
            $form1 = $data?->fsp1_pendaftaran;
            $form2 = $data?->fsp2_logbook;
            $form3 = $data?->fsp3_draft;
            $form4 = $data?->fsp4_nilai;

            $formLabels = ['Pendaftaran', 'Logbook', 'Draft', 'Penilaian'];
        } elseif ($data?->tipe_sidang === 'Seminar Pembahasan') {
            $form1 = $data?->fsh1_pendaftaran;
            $form2 = $data?->fsh2_logbook;
            $form3 = $data?->fsh3_draft;
            $form4 = $data?->fsh4_nilai;

            $formLabels = ['Pendaftaran', 'Logbook', 'Draft', 'Penilaian'];
        } elseif ($data?->tipe_sidang === 'Sidang Ujian') {
            $form1 = $data?->fsu1_buku;
            $form2 = $data?->fsu2_logbook;
            $form3 = $data?->fsu3_ba;
            $form4 = $data?->fsu4_pengesahan;

            $formLabels = ['Buku TA', 'Logbook', 'Berita Acara', 'Pengesahan'];
        }

        return view('xadm.PSD-H1', [
            'data'           => $data,
            'idPengajuan'    => $data?->id,
            'titleTopik'     => $data?->topik?->title,
            'nimUser'        => $data?->user?->nim,
            'namaUser'       => $data?->user?->name,
            'judul'          => $data?->judul,
            'namaDosen'      => $data?->dosen?->name,
            'namaDosen2'     => $data?->dosen2?->name,
            'idDosen'        => $data?->dosen?->id,
            'idDosen2'       => $data?->dosen2?->id,
            'tipe_sidang'    => $data?->tipe_sidang,
            'tipe_pengajuan' => $data?->tipe_pengajuan,
            'penguji'        => $penguji,
            'penguji2'       => $penguji2,
            'form1'          => $form1,
            'form2'          => $form2,
            'form3'          => $form3,
            'form4'          => $form4,
            'formLabels'     => $formLabels,
        ]);
    }

    // POST::REVIEW
    public function x0psdPostReview(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:sidang_submissions,id',
            'status_sidang' => 'required|string|in:Dibuat,Ditolak',
        ]);

        $submission = SidangSubmission::find($request->submission_id);

        if ($request->status_sidang === 'Dibuat') {
            $request->validate([
                'dosen_penguji1'    => 'required|exists:users,id',
                'dosen_penguji2'    => 'nullable|exists:users,id',
                'date1'             => 'required|date',
                'waktu_sidang'      => 'required|date_format:H:i:s',
                'lokasi'            => 'required|string|max:255',
                'skema_pelaksanaan' => 'required|in:Online,Offline',
            ], [
                'dosen_penguji1.required'    => 'Penguji 1 wajib dipilih.',
                'date1.required'             => 'Jadwal sidang harus diisi.',
                'waktu_sidang.required'      => 'Waktu sidang harus diisi.',
                'lokasi.required'            => 'Lokasi harus diisi.',
                'skema_pelaksanaan.required' => 'Skema pelaksanaan harus dipilih.',
            ]);

            if ($request->dosen_penguji1 && $request->dosen_penguji1 == $request->dosen_penguji2) {
                return response()->json([
                    'message' => 'Penguji 1 dan Penguji 2 tidak boleh sama.',
                ], 422);
            }

            if (
                $request->dosen_pembimbing && $request->dosen_pembimbing == $request->dosen_penguji1 ||
                $request->dosen_pembimbing && $request->dosen_pembimbing == $request->dosen_penguji2
            ) {
                return response()->json([
                    'message' => 'Dosen pembimbing tidak boleh sama dengan dosen penguji.',
                ], 422);
            }

            $submission->penguji_id    = $request->dosen_penguji1;
            $submission->penguji2_id   = $request->dosen_penguji2 ?? null;
            $submission->jadwal_sidang = $request->date1;
            $submission->waktu_sidang  = $request->waktu_sidang;
            $submission->lokasi_sidang = $request->lokasi;
            $submission->skema_sidang  = $request->skema_pelaksanaan;
            $submission->link_sidang   = $request->link_sidang;
        } else {
            // Status Ditolak -> kosongkan semua field terkait sidang
            $submission->penguji_id    = null;
            $submission->penguji2_id   = null;
            $submission->jadwal_sidang = null;
            $submission->waktu_sidang  = null;
            $submission->lokasi_sidang = null; // atau null, jika nullable
            $submission->skema_sidang  = null; // atau null, jika nullable
            $submission->link_sidang   = null;
        }

        $submission->status_sidang = $request->status_sidang;
        $submission->save();

        return response()->json([
            'message' => 'Status sidang berhasil diperbarui.',
        ]);
    }

}
