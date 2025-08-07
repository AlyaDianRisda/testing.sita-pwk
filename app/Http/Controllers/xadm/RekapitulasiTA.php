<?php
namespace App\Http\Controllers\xadm;

use App\Http\Controllers\Controller;
use App\Models\ArchivedPeriod;
use App\Models\BimbinganData;
use App\Models\ProposalSubmission;
use App\Models\SidangSubmission;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

setlocale(LC_TIME, 'id_ID'); // Untuk fungsi bawaan PHP
Carbon::setLocale('id');     // Untuk Carbon

class RekapitulasiTA extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // --------------------------------------------------------

    // PAGE::VIEW1
    public function x0rta1View(): View
    {
        $listDosen = User::where('tipe_dos', 'pendamping')->get();
        return view('xadm.RTA1', compact('listDosen'));
    }

    // GET::DATA1
    public function x0rta1Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = ProposalSubmission::with(['topik.dosen', 'user', 'topik.period'])
                ->whereHas('topik.period', function ($q) {
                    $q->where('is_open', true);
                })->whereNotIn('status_pengajuan', ['Ditolak']);

            $recordsTotal = ProposalSubmission::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->whereHas('topik', function ($subQ) use ($search) {
                        $subQ->where('title', 'like', "%{$search}%");
                    })->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    })->orWhereHas('dos_utama', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhereHas('dos_kedua', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhere('status_pengajuan', 'like', "%{$search}%");
                });
            }

            $filteredQuery   = clone $query;
            $recordsFiltered = $filteredQuery->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = ProposalSubmission::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'               => $item->id,
                        'nim'              => optional($item->user)->nim,
                        'nama'             => optional($item->user)->name,
                        'draft_path'       => $item->draft_file_path
                        ? '<a href="' . asset('storage/' . $item->draft_file_path) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'topik_pilihan'    => optional($item->topik)->title,
                        'dos_utama'        => optional($item->dos_utama)->name,
                        'status_pengajuan' => $item->status_pengajuan,
                        'dos_kedua'        => optional($item->dos_kedua)->name ?? 'N/A', // ← Tampilkan nama jika sudah ada
                        'status_pengajuan' => $item->status_pengajuan,
                        'created_at'       => $item->created_at->toDateTimeString(),
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

    // POST::ASSIGN_DOSEN
    public function x0rta1AssignDos(Request $request)
    {
        $request->validate([
            'submission_id'  => 'required|exists:proposal_submissions,id',
            'dosen_kedua_id' => 'required|exists:users,id',
        ]);

        $submission     = ProposalSubmission::find($request->submission_id);
        $bimbingan_data = BimbinganData::where('user_id', $submission->user_id)->first();

        if ($submission->status_pengajuan === 'Diterima') {

            if (! $bimbingan_data) {
                return response()->json(['message' => 'Data bimbingan tidak ditemukan untuk mahasiswa ini.'], 404);
            }

            $submission->dosen2_id = $request->dosen_kedua_id;
            $submission->save();

            $bimbingan_data->dosen2_id = $request->dosen_kedua_id;
            $bimbingan_data->save();

            return response()->json(['message' => 'Pembimbing kedua berhasil diassign']);

        } else {

            return response()->json(['message' => "Dosen pendamping tidak bisa diassign jika dosen utama tidak menerima pengajuan topik mahasiswa ini."], 403);
        }
    }

    // GET::DATA2
    public function x0rta1Data2(Request $request)
    {
        if ($request->ajax()) {
            $query = Topic::with(['period', 'user', 'dosen'])
                ->whereHas('period', function ($q) {
                    $q->where('is_open', true);
                });

            $recordsTotal = Topic::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('focus', 'like', "%{$search}%");
                })->orWhereHas('dosen', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                });
            }

            $filteredQuery   = clone $query;
            $recordsFiltered = $filteredQuery->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = Topic::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->withCount([
                'proposalSubmission',
                'proposalSubmission as validated_submission_count' => function ($query) {
                    $query->where('status_pengajuan', 'Diterima');
                },
            ])->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'nama'              => optional($item->dosen)->name,
                        'status_dosen'      => optional($item->dosen)->tipe_dos,
                        'topik_ta'          => $item->title,
                        'kelompok_keahlian' => $item->focus,
                        'kuota_topik'       => $item->kuota_topik,
                        'jml_diajukan'      => $item->proposal_submission_count,
                        'jml_validasi'      => $item->validated_submission_count,
                        'created_at'        => $item->created_at->toDateTimeString(),
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

    // --------------------------------------------------------

    // PAGE::VIEW2
    public function x0rta2View(): View
    {
        return view('xadm.RTA2');
    }

    // GET::DATA1
    public function x0rta2Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['dosen', 'user', 'topik'])
                ->where('status_sidang', 'selesai');

            $recordsTotal = SidangSubmission::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    })->orWhereHas('dosen', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhereHas('dosen2', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhereHas('penguji', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhereHas('penguji2', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tipe_sidang', 'like', "%{$search}%")
                        ->orWhere('tipe_pengajuan', 'like', "%{$search}%");
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
                        'nim'              => optional($item->user)->nim,
                        'nama'             => optional($item->user)->name,
                        'judul'            => $item->judul,
                        'dos_utama'        => optional($item->dosen)->name,
                        'dos_kedua'        => optional($item->dosen2)->name ?? 'N/A',
                        'penguji1'         => optional($item->penguji)->name,
                        'penguji2'         => optional($item->penguji2)->name ?? 'N/A',
                        'tipe_sidang'      => $item->tipe_sidang,
                        'tipe_pengajuan'   => $item->tipe_pengajuan,
                        'status_pengajuan' => $item->status_sidang,
                        'created_at'       => $item->created_at->toDateTimeString(),

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

    // --------------------------------------------------------

    // PAGE::VIEW3
    public function x0rta3View(): View
    {
        return view('xadm.RTA3');
    }

    // GET::DATA1
    public function x0rta3Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = ArchivedPeriod::query();

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            $data = $query->skip($request->start)
                ->take($request->length)
                ->orderBy('archived_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $review = '';

                    if ($item->file_path) {
                        $url1 = route('x0.RekapitulasiTA3-download1', ['id' => $item->id, 'file' => 'file_path']);
                        $review .= '<div class="d-flex gap-2"><a href="' . $url1 . '" class="btn btn-sm btn-outline-primary flex-fill" style="width: 100px; padding: 4px 6px; font-size: 0.85rem;" target="_blank">Data Proposal</a>';
                    } else {
                        $review .= '<div class="d-flex gap-2"><button class="btn btn-sm btn-outline-secondary flex-fill" style="width: 100px; padding: 4px 6px; font-size: 0.85rem;" disabled>Tidak Tersedia</button>';
                    }

                    if ($item->file_path2) {
                        $url2 = route('x0.RekapitulasiTA3-download2', ['id' => $item->id, 'file' => 'file_path2']);
                        $review .= '<a href="' . $url2 . '" class="btn btn-sm btn-outline-primary flex-fill" style="width: 100px; padding: 4px 6px; font-size: 0.85rem;" target="_blank">Data Topik</a>';
                    } else {
                        $review .= '<button class="btn btn-sm btn-outline-secondary flex-fill"  style="width: 100px; padding: 4px 6px; font-size: 0.85rem;" disabled>Tidak Tersedia</button>';
                    }

                    return [
                        'periode'    => $item->name,
                        'tanggal'    => Carbon::parse($item->start_date)->translatedFormat('d M Y') . ' s.d. ' . Carbon::parse($item->end_date)->translatedFormat('d M Y'),
                        'tipe'       => $item->type,
                        'review'     => $review,
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

    // GET::DOWNLOAD1
    public function x0rta3Download1($id)
    {
        $arsip = ArchivedPeriod::findOrFail($id);

        if (! $arsip->file_path || ! Storage::disk('local')->exists($arsip->file_path)) {
            return abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download($arsip->file_path);
    }

    // GET::DOWNLOAD2
    public function x0rta3Download2($id)
    {
        $arsip = ArchivedPeriod::findOrFail($id);

        if (! $arsip->file_path2 || ! Storage::disk('local')->exists($arsip->file_path2)) {
            return abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download($arsip->file_path2);
    }

}
