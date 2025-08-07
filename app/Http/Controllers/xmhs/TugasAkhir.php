<?php
namespace App\Http\Controllers\xmhs;

use App\Http\Controllers\Controller;
use App\Models\BimbinganData;
use App\Models\ProposalSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TugasAkhir extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // --------------------------------------------------------

    // PAGE::VIEW1
    public function x2tak1View(Request $request): View
    {
        $idTopik    = urldecode($request->get('idTopik'));
        $titleTopik = urldecode($request->get('titleTopik'));
        $idDosen    = urldecode($request->get('idDosen'));
        $namaDosen  = urldecode($request->get('namaDosen'));

        return view('xmhs.TAK1', compact('idTopik', 'idDosen', 'namaDosen', 'titleTopik'));
    }

    // GET::DATA1
    public function x2tak1Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = ProposalSubmission::with(['topik', 'user'])
                ->where('user_id', auth()->id());

            $recordsTotal = ProposalSubmission::count();

            // if ($request->has('search') && $request->search['value'] != '') {
            //     $search = $request->search['value'];
            //     $query->where(function ($q) use ($search) {
            //         $q->where('status_pengajuan', 'like', "%{$search}%");
            //     });
            // }

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
                        'topik'           => optional($item->topik)->title,
                        'dosen'           => optional($item->topik->dosen)->name,
                        'periode_topik'   => optional($item->topik->period)->name,
                        'rancangan_judul' => $item->rancangan_judul,
                        'draft_proposal'  => $item->draft_file_path
                        ? '<a href="' . asset('storage/' . $item->draft_file_path) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'          => $item->status_pengajuan,
                        'catatan'         => $item->catatan_validasi,
                        'created_at'      => $item->created_at->toDateTimeString(),
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

    // HAS::CEK_PENDING
    public function hasPendingSubmission()
    {
        $userId = auth()->id();

        $hasPending = ProposalSubmission::where('user_id', $userId)
            ->where('status_pengajuan', 'Pending')
            ->exists();

        return $hasPending;
    }

    // HAS::CEK_DITERIMA
    public function hasAcceptedSubmission()
    {
        $userId = auth()->id();

        $hasPending = ProposalSubmission::where('user_id', $userId)
            ->where('status_pengajuan', 'Diterima')
            ->exists();

        return $hasPending;
    }

    // POST::SUBMISSION_PROPOSAL
    public function x2tak1SubmitProp(Request $request)
    {
        if ($this->hasPendingSubmission()) {
            return response()->json([
                'message' => 'Anda masih memiliki pengajuan proposal yang berstatus Pending.',
            ], 409); // Conflict
        }
        if ($this->hasAcceptedSubmission()) {
            return response()->json([
                'message' => 'Anda sudah memiliki pengajuan proposal yang berstatus Diterima.',
            ], 409); // Conflict
        }

        $request->validate([
            'id_topik'        => 'required|integer|exists:topics,id',
            'id_dosen'        => 'required|integer|exists:users,id',
            'rancangan_judul' => 'required|string|max:255|unique:proposal_submissions,rancangan_judul',
            'draft_proposal'  => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'rancangan_judul.unique' => 'Judul yang sama sudah pernah diajukan.',
        ]);

        $filePath = $request->file('draft_proposal')->store('proposals', 'public');

        $proposal = ProposalSubmission::create([
            'user_id'          => auth()->id(),
            'dosen_id'         => $request->id_dosen,
            'topik_id'         => $request->id_topik,
            'rancangan_judul'  => $request->rancangan_judul,
            'draft_file_path'  => $filePath,
            'status_pengajuan' => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan proposal berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // --------------------------------------------------------

    // PAGE::VIEW
    public function x2tak2View(): View
    {
        $mahasiswaId = auth()->id();

        $bimbingan = BimbinganData::with(['dosen', 'dosen2'])
            ->where('user_id', $mahasiswaId)
            ->where('status_bimbingan', 'Berjalan')
            ->latest()
            ->first();
        return view('xmhs.TAK2', [
            'bimbingan' => $bimbingan,
        ]);
    }

    // GET::DATA1
    public function x2tak2Data1(Request $request)
    {
        if ($request->ajax()) {
            $query = BimbinganData::with(['topik', 'dosen', 'dosen2'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                });
            $recordsTotal = BimbinganData::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->whereHas('topik', function ($subQ) use ($search) {
                        $subQ->where('title', 'like', "%{$search}%");
                    })->orWhereHas('user', function ($subQ) use ($search) {
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

                $validColumns = BimbinganData::getTableColumns();
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
                        'topik'            => optional($item->topik)->title,
                        'judul'            => $item->judul,
                        'dosen_name'       => optional($item->dosen)->name,
                        'dosen2_name'      => optional($item->dosen2)->name ?? 'N/A',
                        'status_bimbingan' => $item->status_bimbingan,
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

}
