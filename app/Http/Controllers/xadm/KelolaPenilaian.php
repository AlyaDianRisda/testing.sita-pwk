<?php
namespace App\Http\Controllers\xadm;

use App\Http\Controllers\Controller;
use App\Models\SidangSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelolaPenilaian extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x0kpnView(): View
    {
        return view('xadm.KPN');
    }

    // GET::DATA1
    public function x0kpnData1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'dosen'])
                ->where(function ($q) {
                    $q->where('status_sidang', 'dinilai');
                });
            $recordsTotal = SidangSubmission::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    })->orWhereHas('dosen', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhere('tipe_sidang', 'like', "%{$search}%");
                });
            }

            $filteredQuery   = clone $query;
            $recordsFiltered = $filteredQuery->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = SidangSubmission::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'nim'         => optional($item->user)->nim,
                        'nama'        => optional($item->user)->name,
                        'tipe_sidang' => $item->tipe_sidang,
                        'pembimbing'  => optional($item->dosen)->name,
                        'review'      => $item->id
                        ? '<div class="d-flex"><a href="' . route('x0.KelolaPenilaian-json2', ['id' => $item->id]) . '" class="btn btn-primary btn-sm flex-fill" style="min-width: 70px; padding: 4px 6px; font-size: 0.85rem;">Manage</a>'
                        : '',
                        'created_at'  => $item->created_at->toDateTimeString(),
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

    // GET::DATA2
    public function x0kpnData2(Request $request)
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

        return view('xadm.KPN-H1', [
            'data'           => $data,
            'idPengajuan'    => $data?->id,
            'titleTopik'     => $data?->topik?->title,
            'nimUser'        => $data?->user?->nim,
            'namaUser'       => $data?->user?->name,
            'judul'          => $data?->judul,
            'namaDosen'      => $data?->dosen?->name,
            'idDosen'        => $data?->dosen?->id,
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

    // POST::KONFIRMASI
    public function x0kpnKonfirmasi(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:sidang_submissions,id',
            'status_sidang' => 'required|in:Selesai,Dinilai',
        ]);

        $submission                = SidangSubmission::find($request->submission_id);
        $submission->status_sidang = $request->status_sidang; // pakai dari request
        $submission->save();

        return response()->json([
            'message' => 'Status sidang berhasil diperbarui.',
        ]);
    }
}
