<?php
namespace App\Http\Controllers\xmhs;

use App\Http\Controllers\Controller;
use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

setlocale(LC_TIME, 'id_ID');
Carbon::setLocale('id'); 

class JadwalSidang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x2jsdView(): View
    {
        $data = SidangSubmission::with(['user', 'topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->first();

        return view('xmhs.JSD', [
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'namaDosen'  => $data?->dosen?->name,

        ]);
    }

    // GET::DATA1
    public function x2jsdData1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen', 'dosen2', 'penguji', 'penguji2'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('status_sidang', 'Dibuat')
                ->orWhere('status_sidang', 'Selesai');

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
                        'id'          => $item->id,
                        'tipe_sidang' => $item->tipe_sidang,
                        'dosen'       => optional($item->dosen)->name,
                        'dosen2'      => optional($item?->dosen2)->name,
                        'penguji'     => optional($item->penguji)->name,
                        'penguji2'    => optional($item?->penguji2)->name,
                        'tanggal'     => Carbon::parse($item->jadwal_sidang)->translatedFormat('d M Y'),
                        'waktu'       => $item->waktu_sidang,
                        'status'      => $item->status_sidang,
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

}
