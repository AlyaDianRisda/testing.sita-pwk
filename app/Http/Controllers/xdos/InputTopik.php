<?php
namespace App\Http\Controllers\xdos;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\ProposalKuotaDosen;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InputTopik extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::TOPIK
    public function x1itpView(): View
    {
        $data = ProposalKuotaDosen::with(['dosen', 'period'])
            ->where('dosen_id', auth()->id())
            ->whereHas('period', function ($query) {
                $query->where('is_open', true);
            })
            ->first();

        return view('xdos.ITP', [
            'kuota_total' => $data?->kuota_total,
            'kuota_tersisa'  => $data?->kuota_tersisa,
        ]);
    }

    // GET::DATA1
    public function x1itpData1(Request $request)
    {
        if ($request->ajax()) {
            $query = Topic::with(['period', 'user'])
                ->where('dosen_id', auth()->id());

            $recordsTotal = Topic::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('focus', 'like', "%{$search}%")
                        ->orWhereHas('period', function ($subQ) use ($search) {
                            $subQ->where('name', 'like', "%{$search}%");
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
            ])
                ->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'               => $item->id,
                        'title'            => $item->title,
                        'focus'            => $item->focus,
                        'kuota_topik'      => $item->kuota_topik,
                        'period_name'      => optional($item->period)->name,
                        'submission_count' => $item->proposal_submission_count,
                        'validated_sc'     => $item->validated_submission_count,
                        'created_at'       => $item->created_at,
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

    // POST::FORM1
    public function x1itpTambahTopik(Request $request)
    {
        if (auth()->user()->type !== 'dosen') {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $openPeriod = Period::where('is_open', true)->where('type', 'Pengajuan Proposal')->first();

        if (is_null($openPeriod)) {
            return response()->json(['message' => 'Tidak ada periode pengajuan proposal yang sedang terbuka, cek halaman Info Periode.'], 400);
        }
        $periodIdToUse = $openPeriod->id;

        $data = ProposalKuotaDosen::with(['dosen', 'period'])
            ->where('dosen_id', auth()->id())
            ->whereHas('period', function ($query) {
                $query->where('is_open', true);
            })
            ->first();

        $kuotaMaks = $data?->kuota_tersisa ?? 0; // kuota_tersisa = kuota yang masih tersedia

        $request->validate([
            'title' => 'required|string|max:255',
            'focus' => 'required|string|max:255',
            'kuota' => "required|integer|min:1|max:$kuotaMaks",
        ], [
            'title.required' => 'Title wajib diisi.',
            'focus.required' => 'Kelompok Keahlian wajib diisi',
            'kuota.required' => 'Kuota wajib diisi.',
            'kuota.integer'  => 'Kuota harus berupa angka.',
            'kuota.max'      => "Input melebihi sisa kuota anda ($kuotaMaks mahasiswa).",
        ]);

        Topic::create([
            'title'       => $request->title,
            'focus'       => $request->focus,
            'kuota_topik' => $request->kuota,
            'period_id'   => $periodIdToUse,
            'dosen_id'    => auth()->id(),
            'user_id'     => null,
        ]);

        if ($data) {
            // Kurangi kuota_tersisa (jumlah kuota tersisa) sesuai kuota topik baru
            $data->kuota_tersisa -= $request->kuota;

            // Simpan perubahan ke database
            $data->save();
        }

        return response()->json(['message' => 'Topik berhasil ditambahkan']);
    }


    // DELETE::HAPUS_TOPIK
    public function x1itpHapusTopik($id)
    {
        if (auth()->user()->type !== 'dosen') {
            return response()->json(['message' => 'Forbidden.'], 403);
        }
        $topicId = Topic::findOrFail($id);

        // Ambil data kuota dosen berdasarkan dosen dan periodenya
        $kuotaDosen = ProposalKuotaDosen::where('dosen_id', auth()->id())
            ->where('period_id', $topicId->period_id)
            ->first();

        // Jika ada, kembalikan kuota bimbingan sesuai kuota_topik yang dihapus
        if ($kuotaDosen) {
            $kuotaDosen->kuota_tersisa += $topicId->kuota_topik;
            $kuotaDosen->save();
        }

        $topicId->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}