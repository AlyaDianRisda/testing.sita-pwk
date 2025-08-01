<?php
namespace App\Http\Controllers\xdos;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\ProposalKuotaDosen;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodeTA extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x1ptaView(): View
    {
        return view('xdos.PTA');
    }
    // GET::DATA1
    public function x1ptaData1(Request $request)
    {
        if ($request->ajax()) {
            $query = Period::query();

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
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
                ->get(['id', 'name', 'type', 'start_date', 'end_date', 'is_open'])
                ->map(function ($item) {
                    return [
                        'name'       => $item->name,
                        'type'       => $item->type,
                        'start_date' => $item->start_date,
                        'end_date'   => $item->end_date,
                        'is_open'    => $item->is_open
                        ? '<div class="d-flex"><span class="badge-status badge-open flex-fill">Dibuka</span></div>'
                        : '<div class="d-flex"><span class="badge-status badge-closed flex-fill">Ditutup</span></div>',
                        'created_at' => $item->created_at,
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