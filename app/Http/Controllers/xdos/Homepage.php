<?php
namespace App\Http\Controllers\xdos;

use App\Http\Controllers\Controller;
use App\Models\BimbinganData;
use App\Models\ProposalSubmission;
use App\Models\SidangSubmission;
use Illuminate\View\View;

class Homepage extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x1homeView(): View
    {
        $userId = auth()->id();

        $jumlahMahasiswa = BimbinganData::where('dosen_id', $userId)
            ->where('status_bimbingan', 'Berjalan')
            ->distinct('user_id')
            ->count('user_id');

        $pengajuanTopik = ProposalSubmission::where('dosen_id', $userId)
            ->where('status_pengajuan', 'Pending')
            ->distinct('user_id')
            ->count('user_id');

        $permintaanSidang = SidangSubmission::where('dosen_id', $userId)
            ->where('status_sidang', 'Dibuat')
            ->distinct('user_id')
            ->count('user_id');

        $permintaanNilai = SidangSubmission::where('dosen_id', $userId)
            ->where('status_sidang', '!=', 'Selesai')
            ->distinct('user_id')
            ->count('user_id');

        return view('xdos.Home', compact('jumlahMahasiswa', 'pengajuanTopik', 'permintaanSidang', 'permintaanNilai'));
    }

}
