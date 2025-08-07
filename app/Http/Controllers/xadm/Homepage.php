<?php
namespace App\Http\Controllers\xadm;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\SidangSubmission;

class Homepage extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x0homeView(): View
    {
        $userId = auth()->id();

        $permintaanSidang = SidangSubmission::where('status_sidang', 'Pending')
            ->distinct('user_id')
            ->count('user_id');

        $permintaanNilai = SidangSubmission::where('status_sidang', 'Dinilai')
            ->distinct('user_id')
            ->count('user_id');

        return view('xadm.Home', compact('permintaanSidang', 'permintaanNilai'));
    }

}
