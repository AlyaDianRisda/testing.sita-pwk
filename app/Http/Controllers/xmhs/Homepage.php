<?php
namespace App\Http\Controllers\xmhs;

use App\Http\Controllers\Controller;
use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Homepage extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x2homeView(): View
    {
        $userId = auth()->id();

        $sidangTerakhir = SidangSubmission::where('user_id', $userId)
            ->where('status_sidang', 'Selesai')
            ->latest()
            ->first();

        $pengajuanTerbaru = SidangSubmission::where('user_id', $userId)
            ->where('status_sidang', '!=', 'Selesai')
            ->latest()
            ->first();

        return view('xmhs.Home', compact('sidangTerakhir', 'pengajuanTerbaru'));
    }

    // GET::DATA2
    public function x2homeData2(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::query()
                ->where('status_sidang', 'Dibuat');

            $recordsTotal = $query->count();

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

            $recordsFiltered = $query->count();

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
                        'mhs_name'       => optional($item->user)->name,
                        'tipe_sidang'    => $item->tipe_sidang,
                        'tipe_pengajuan' => $item->tipe_pengajuan,
                        'tanggal_sidang' => $item->jadwal_sidang,
                        'waktu_sidang'   => $item->waktu_sidang,
                        'pembimbing'     => optional($item->dosen)->name,
                        'pendamping'     => optional($item?->dosen2)->name ?? 'N/A',
                        'penguji_1'      => optional($item->penguji)->name,
                        'penguji_2'      => optional($item?->penguji2)->name ?? 'N/A',
                        'lokasi_sidang'  => $item->lokasi_sidang,
                        'skema_sidang'   => $item->skema_sidang,
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

}
