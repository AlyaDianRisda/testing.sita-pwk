<?php
namespace App\Http\Controllers\xdos;

use App\Http\Controllers\Controller;
use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

setlocale(LC_TIME, 'id_ID'); // Untuk fungsi bawaan PHP
Carbon::setLocale('id');     // Untuk Carbon

class AgendaSidang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW1
    public function x1asdView(): View
    {
        return view('xdos.ASD');
    }

    // GET::DATA1
    public function x1asdData1(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen', 'dosen2', 'penguji', 'penguji2'])
                ->where(function ($q) {
                    $q->where('dosen_id', auth()->id())
                        ->orWhere('dosen2_id', auth()->id())
                        ->orWhere('penguji_id', auth()->id())
                        ->orWhere('penguji2_id', auth()->id());
                })
                ->where('status_sidang', 'Dibuat');

            $recordsTotal = SidangSubmission::count();

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

                $validColumns = SidangSubmission::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    $loggedInDosenId = auth()->id();

                    if ($item->dosen_id === $loggedInDosenId) {
                        $role = 'Pembimbing 1';
                    } elseif ($item->dosen2_id === $loggedInDosenId) {
                        $role = 'Pembimbing 2';
                    } elseif ($item->penguji_id === $loggedInDosenId) {
                        $role = 'Penguji 1';
                    } elseif ($item->penguji2_id === $loggedInDosenId) {
                        $role = 'Penguji 2';
                    } else {
                        $role = '-';
                    }

                    return [
                        'id'          => $item->id,
                        'aksi'        => '<div class="d-flex"><button class="btn btn-sm btn-primary btn-detail flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;"
                                                    data-role="' . e($role) . '"
                                                    data-topik="' . e(optional($item->topik)->title) . '"
                                                    data-judul="' . e($item->judul) . '"
                                                    data-tipe="' . e($item->tipe_sidang) . '"
                                                    data-pengajuan="' . e($item->skema_sidang) . '"
                                                    data-tanggal="' . (! empty($item->jadwal_sidang)
                                                        ? Carbon::parse($item->jadwal_sidang)->translatedFormat('d M Y (l)')
                                                        : 'N/A') . '"
                                                    data-waktu="' . (! empty($item->waktu_sidang)
                                                        ? Carbon::parse($item->waktu_sidang)->format('H:i') . ' WIB'
                                                        : 'N/A') . '"
                                                    data-lokasi="' . e($item->lokasi_sidang) . '"
                                                    data-skema="' . e($item->skema_sidang) . '"
                                                    data-link="' . e($item->link_sidang) . '">
                                                    Detail
                                                </button>',
                        'tipe_sidang' => $item->tipe_sidang,
                        'role'        => $role,
                        'nim'         => optional($item->user)->nim,
                        'nama'        => optional($item->user)->name,
                        'tanggal'     => $item->jadwal_sidang,
                        'waktu'       => $item->waktu_sidang,
                        'lokasi'      => $item->lokasi_sidang,
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
