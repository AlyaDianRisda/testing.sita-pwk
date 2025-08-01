<?php
namespace App\Http\Controllers\xmhs;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PilihanTopik extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x2pltView(): View
    {
        return view('xmhs.PLT');
    }

    // GET::DATA1
    public function x2pltData1(Request $request)
    {
        if ($request->ajax()) {
            $query = Topic::with(['dosen', 'period'])
                ->whereHas('period', function ($q) {
                    $q->where('is_open', true);
                });

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('focus', 'like', "%{$search}%")
                        ->orWhereHas('dosen', function ($subQ) use ($search) {
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
                    $idTopik    = urlencode($item->id ?? '');
                    $titleTopik = urlencode($item->title ?? '');
                    $idDosen    = urlencode(optional($item->dosen)->id ?? '');
                    $namaDosen  = urlencode(optional($item->dosen)->name ?? '');

                    $link = route('x2.TugasAkhir-1', ['idTopik' => $idTopik, 'idDosen' => $idDosen, 'namaDosen' => $namaDosen, 'titleTopik' => $titleTopik]);

                    return [
                        'id'               => $item->id,
                        'period_name'      => optional($item->period)->name,
                        'title'            => $item->title,
                        'focus'            => $item->focus,
                        'dosen_name'       => optional($item->dosen)->name,
                        'kuota'            => $item->kuota_topik,
                        'submission_count' => $item->proposal_submission_count,
                        'validated_sc'     => $item->validated_submission_count,
                        'submission'       => $item->proposal_submission_count >= 15 || $item->kuota_topik == $item->validated_submission_count
                        ? '<button class="btn btn-sm btn-secondary flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" disabled>Penuh</button>'
                        : '<a href="' . $link . '" class="btn btn-sm btn-primary flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;">Ajukan</a>',
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
