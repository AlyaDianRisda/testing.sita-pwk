<?php
namespace App\Http\Controllers\xmhs;

use App\Http\Controllers\Controller;
use App\Models\BimbinganData;
use App\Models\Period;
use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranSidang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // --------------------------------------------------------

    // PAGE::VIEW1
    public function x2pds1View(): View
    {
        $data = BimbinganData::with(['topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->where('status_bimbingan', 'Berjalan')
            ->first();

        return view('xmhs.PDS1', [
            'idTopik'    => $data?->topik?->id,
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'idDosen'    => $data?->dosen?->id,
            'idDosen2'   => $data?->dosen2?->id,
            'namaDosen'  => $data?->dosen?->name,
            'namaDosen2' => $data?->dosen2?->name,

        ]);
    }

    // GET::DATA1
    public function x2pds1Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Seminar Proposal');

            $recordsTotal = SidangSubmission::count();

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
                        'id'             => $item->id,
                        'tipe_sidang'    => $item->tipe_sidang,
                        'tipe_pengajuan' => $item->tipe_pengajuan,
                        'form1'          => $item->fsp1_pendaftaran
                        ? '<a href="' . asset('storage/' . $item->fsp1_pendaftaran) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'          => $item->fsp2_logbook
                        ? '<a href="' . asset('storage/' . $item->fsp2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'          => $item->fsp3_draft
                        ? '<a href="' . asset('storage/' . $item->fsp3_draft) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'         => $item->status_sidang,
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

    // POST::REGISTRASI_PROPOSAL
    public function x2pds1RegProp(Request $request)
    {
        $request->validate([
            'id_topik'       => 'required|integer|exists:topics,id',
            'form1'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'tipe_pengajuan' => 'required|in:Pengajuan Periode,Pengajuan Mandiri',
            'waktu_sidang'   => 'nullable|date_format:H:i:s',
        ]);

        $tipePengajuan = $request->input('tipe_pengajuan');

        // Jika tipe pengajuan adalah "Pengajuan Periode", cek apakah ada periode aktif
        if ($tipePengajuan === 'Pengajuan Periode') {
            $activePeriode = Period::where('type', 'Seminar Proposal')
                ->where('is_open', true)
                ->first();

            if (! $activePeriode) {
                return response()->json([
                    'message' => 'Periode pengajuan Seminar Proposal saat ini belum dibuka.',
                ], 403);
            }
        }

        // Cek apakah user sudah punya pengajuan dengan tipe sidang "Seminar Proposal" dan status ≠ Ditolak
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Proposal')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        // Upload file
        $filePath1 = $request->file('form1')->store('sempro/pendaftaran', 'public');
        $filePath2 = $request->file('form2')->store('sempro/logbook', 'public');
        $filePath3 = $request->file('form3')->store('sempro/draft', 'public');

        // Simpan data ke DB
        $proposal = SidangSubmission::create([
            'user_id'          => auth()->id(),
            'judul'            => $request->inp_judul,
            'topik_id'         => $request->id_topik,
            'dosen_id'         => $request->id_dosen,
            'dosen2_id'        => $request->id_dosen2,
            'fsp1_pendaftaran' => $filePath1,
            'fsp2_logbook'     => $filePath2,
            'fsp3_draft'       => $filePath3,
            'tipe_sidang'      => 'Seminar Proposal', // default, karena ini controller khusus SEMPRO
            'tipe_pengajuan'   => $tipePengajuan,
            'jadwal_sidang'    => $request->date1,
            'waktu_sidang'     => $request->waktu_sidang,
            'status_sidang'    => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan seminar proposal berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // --------------------------------------------------------

    // PAGE::VIEW2
    public function x2pds2View(): View
    {
        $data = BimbinganData::with(['topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->where('status_bimbingan', 'Berjalan')
            ->first();

        return view('xmhs.PDS2', [
            'idTopik'    => $data?->topik?->id,
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'idDosen'    => $data?->dosen?->id,
            'idDosen2'   => $data?->dosen2?->id,
            'namaDosen'  => $data?->dosen?->name,
            'namaDosen2' => $data?->dosen2?->name,

        ]);
    }

    // GET::DATA1
    public function x2pds2Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Seminar Pembahasan');

            $recordsTotal = SidangSubmission::count();

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
                        'id'             => $item->id,
                        'tipe_sidang'    => $item->tipe_sidang,
                        'tipe_pengajuan' => $item->tipe_pengajuan,
                        'form1'          => $item->fsh1_pendaftaran
                        ? '<a href="' . asset('storage/' . $item->fsh1_pendaftaran) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'          => $item->fsh2_logbook
                        ? '<a href="' . asset('storage/' . $item->fsh2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'          => $item->fsh3_draft
                        ? '<a href="' . asset('storage/' . $item->fsh3_draft) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'         => $item->status_sidang,
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

    // POST::REGISTRASI_PEMBAHASAN
    public function x2pds2RegHas(Request $request)
    {
        $tipePengajuan = $request->input('tipe_pengajuan');

        // Jika tipe pengajuan adalah "Pengajuan Periode", cek apakah ada periode aktif
        if ($tipePengajuan === 'Pengajuan Periode') {
            $activePeriode = Period::where('type', 'Seminar Pembahasan')
                ->where('is_open', true)
                ->first();

            if (! $activePeriode) {
                return response()->json([
                    'message' => 'Periode pengajuan Seminar Pembahasan saat ini belum dibuka.',
                ], 403);
            }
        }

        // Cek apakah user sudah memiliki pengajuan SEMHAS yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Pembahasan')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $sempro = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Proposal')
            ->where('status_sidang', 'Selesai')
            ->first();

        if (! $sempro) {
            return response()->json([
                'message' => 'Pengajuan Seminar Pembahasan ditolak. Anda belum menyelesaikan Seminar Proposal.',
            ], 403);
        }

        $request->validate([
            'id_topik'       => 'required|integer|exists:topics,id',
            'form1'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'tipe_pengajuan' => 'required|in:Pengajuan Periode,Pengajuan Mandiri',
            'waktu_sidang'   => 'nullable|date_format:H:i:s',
        ]);

        $filePath1 = $request->file('form1')->store('semhas/pendaftaran', 'public');
        $filePath2 = $request->file('form2')->store('semhas/logbook', 'public');
        $filePath3 = $request->file('form3')->store('semhas/draft', 'public');

        $proposal = SidangSubmission::create([
            'user_id'          => auth()->id(),
            'judul'            => $request->inp_judul,
            'topik_id'         => $request->id_topik,
            'dosen_id'         => $request->id_dosen,
            'dosen2_id'        => $request->id_dosen2,
            'penguji_id'       => $sempro->penguji_id,
            'penguji2_id'      => $sempro->penguji2_id,
            'fsh1_pendaftaran' => $filePath1,
            'fsh2_logbook'     => $filePath2,
            'fsh3_draft'       => $filePath3,
            'tipe_sidang'      => 'Seminar Pembahasan',
            'tipe_pengajuan'   => $tipePengajuan,
            'jadwal_sidang'    => $request->date1,
            'waktu_sidang'     => $request->waktu_sidang,
            'status_sidang'    => 'Pending',

        ]);

        return response()->json([
            'message' => 'Pengajuan seminar pembahasan berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // --------------------------------------------------------

    // PAGE::VIEW3
    public function x2pds3View(): View
    {
        $data = BimbinganData::with(['topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->where('status_bimbingan', 'Berjalan')
            ->first();

        return view('xmhs.PDS3', [
            'idTopik'    => $data?->topik?->id,
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'idDosen'    => $data?->dosen?->id,
            'idDosen2'   => $data?->dosen2?->id,
            'namaDosen'  => $data?->dosen?->name,
            'namaDosen2' => $data?->dosen2?->name,

        ]);
    }

    // GET::DATA1
    public function x2pds3Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Sidang Ujian');

            $recordsTotal = SidangSubmission::count();

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
                        'id'             => $item->id,
                        'tipe_sidang'    => $item->tipe_sidang,
                        'tipe_pengajuan' => $item->tipe_pengajuan,
                        'form1'          => $item->fsu1_buku
                        ? '<a href="' . asset('storage/' . $item->fsu1_buku) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'          => $item->fsu2_logbook
                        ? '<a href="' . asset('storage/' . $item->fsu2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'          => $item->fsu3_ba
                        ? '<a href="' . asset('storage/' . $item->fsu3_ba) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form4'          => $item->fsu4_pengesahan
                        ? '<a href="' . asset('storage/' . $item->fsu4_pengesahan) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'         => $item->status_sidang,
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

    // POST::REGISTRASI_SIDANG
    public function x2pds3RegSid(Request $request)
    {
        $tipePengajuan = $request->input('tipe_pengajuan');

        // Jika tipe pengajuan adalah "Pengajuan Periode", cek apakah ada periode aktif
        if ($tipePengajuan === 'Pengajuan Periode') {
            $activePeriode = Period::where('type', 'Sidang Ujian')
                ->where('is_open', true)
                ->first();

            if (! $activePeriode) {
                return response()->json([
                    'message' => 'Periode pengajuan Sidang Ujian saat ini belum dibuka.',
                ], 403);
            }
        }

        // Cek apakah user sudah memiliki pengajuan SIDU yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Sidang Ujian')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $semhas = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Pembahasan')
            ->where('status_sidang', 'Selesai')
            ->first();

        if (! $semhas) {
            return response()->json([
                'message' => 'Pengajuan Sidang Ujian ditolak. Anda belum menyelesaikan Seminar Pembahasan.',
            ], 403);
        }

        $request->validate([
            'id_topik'       => 'required|integer|exists:topics,id',
            'form1'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'          => 'required|file|mimes:pdf,doc,docx|max:2048',
            'tipe_pengajuan' => 'required|in:Pengajuan Periode,Pengajuan Mandiri',
            'waktu_sidang'   => 'nullable|date_format:H:i:s',
        ]);

        $filePath1 = $request->file('form1')->store('sidang/buku', 'public');
        $filePath2 = $request->file('form2')->store('sidang/logbook', 'public');
        $filePath3 = $request->file('form3')->store('sidang/ba', 'public');
        $filePath3 = $request->file('form3')->store('sidang/pengesahan', 'public');

        $proposal = SidangSubmission::create([
            'user_id'         => auth()->id(),
            'judul'           => $request->inp_judul,
            'topik_id'        => $request->id_topik,
            'dosen_id'        => $request->id_dosen,
            'dosen2_id'       => $request->id_dosen2,
            'penguji_id'      => $semhas->penguji_id,
            'penguji2_id'     => $semhas->penguji2_id,
            'fsu1_buku'       => $filePath1,
            'fsu2_logbook'    => $filePath2,
            'fsu3_ba'         => $filePath3,
            'fsu4_pengesahan' => $filePath3,
            'tipe_sidang'     => 'Sidang Ujian',
            'tipe_pengajuan'  => $tipePengajuan,
            'jadwal_sidang'   => $request->date1,
            'waktu_sidang'    => $request->waktu_sidang,
            'status_sidang'   => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan sidang ujian berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // --------------------------------------------------------

    // PAGE::VIEW4
    public function x2pds4View(): View
    {
        return view('xmhs.PDS4');
    }

    // GET::DATA1
    public function x2pds4Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id())
                        ->where('status_sidang', 'Selesai');
                });

            $recordsTotal = SidangSubmission::count();

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
                        'id'            => $item->id,
                        'tipe_sidang'   => $item->tipe_sidang,
                        'dosen'         => optional($item->dosen)->name,
                        'status_sidang' => $item->status_sidang,
                        'ket_hasil'     => $item->hasil,
                        'aksi'          => ($item->tipe_sidang === 'Sidang Ujian')
                        ? '<div class="d-flex"><a href="' . route('x2.PendaftaranSidang4-json2', ['id' => $item->id]) . '" class="btn btn-primary btn-sm flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;">Perubahan Nilai</a>'
                        : '',
                        'created_at'    => $item->created_at->toDateTimeString(),
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
    public function x2pds4Data2(Request $request)
    {
        $data = SidangSubmission::with(['topik', 'user', 'dosen', 'dosen2', 'penguji', 'penguji2'])
            ->where('id', $request->id)
            ->first();

        // kasih logika ini

        return view('xmhs.PDS4-H1', [
            'data'        => $data,
            'idPengajuan' => $data?->id,
            'idTopik'     => $data?->topik?->id,
            'titleTopik'  => $data?->topik?->title,
            'nimUser'     => $data?->user?->nim,
            'namaUser'    => $data?->user?->name,
            'judul'       => $data?->judul,
            'namaDosen'   => $data?->dosen?->name,
            'namaDosen2'  => $data?->dosen2?->name,
            'idDosen'     => $data?->dosen?->id,
            'tipe_sidang' => $data?->tipe_sidang,
            'penguji'     => $data?->penguji?->name,
            'penguji2'    => $data?->penguji2?->name,
            'form1'       => $data?->form1,
            'form2'       => $data?->form2,
            'form3'       => $data?->form3,
            'form4'       => $data?->form4,
        ]);
    }

    // POST::REQUEST_PERUBAHAN
    public function x2pds4RequestUbah(Request $request)
    {
        // Cek apakah user sudah memiliki pengajuan SIDU yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Ubah Nilai')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $sidu = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Sidang Ujian')
            ->where('status_sidang', 'Selesai')
            ->first();

        if (! $sidu) {
            return response()->json([
                'message' => 'Pengajuan Ubah Nilai ditolak. Anda belum menyelesaikan Sidang Ujian.',
            ], 403);
        }

        $request->validate([
            'id_topik' => 'required|integer|exists:topics,id',
            'form1'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form4'    => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath1 = $request->file('form1')->store('ubah/buku', 'public');
        $filePath2 = $request->file('form2')->store('ubah/logbook', 'public');
        $filePath3 = $request->file('form3')->store('ubah/ba', 'public');
        $filePath4 = $request->file('form4')->store('ubah/pengesahan', 'public');

        $proposal = SidangSubmission::create([
            'user_id'        => auth()->id(),
            'judul'          => $request->inp_judul,
            'topik_id'       => $request->id_topik,
            'dosen_id'       => $request->id_dosen,
            'penguji_id'     => $request->penguji_id,
            'penguji2_id'    => $request->penguji2_id,
            'un1_buku'       => $filePath1,
            'un2_logbook'    => $filePath2,
            'un3_ba'         => $filePath3,
            'un4_pengesahan' => $filePath4,
            'tipe_sidang'    => 'Ubah Nilai',
            'status_sidang'  => 'Dinilai',
        ]);

        return response()->json([
            'message' => 'Pengajuan ubah nilai berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // GET::DATA_PERUBAHAN
    public function getDataUbah(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Ubah Nilai');

            $recordsTotal = SidangSubmission::count();

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
                        'id'         => $item->id,
                        'form1'      => $item->un1_buku
                        ? '<a href="' . asset('storage/' . $item->un1_buku) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'      => $item->un2_logbook
                        ? '<a href="' . asset('storage/' . $item->un2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'      => $item->un3_ba
                        ? '<a href="' . asset('storage/' . $item->un3_ba) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form4'      => $item->un4_pengesahan
                        ? '<a href="' . asset('storage/' . $item->un4_pengesahan) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'     => $item->status_sidang,
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

}
