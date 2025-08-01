<?php

/** 
 * User type code
 *   x0 = Administrator / Koor TA
 *   x1 = Dosen
 *   x2 = User / Mahasiswa
*/

use App\Http\Controllers\xadm\Homepage as x0Homepage;
use App\Http\Controllers\xadm\PeriodeTA as x0PeriodeTA;
use App\Http\Controllers\xadm\KelolaPOB as x0KelolaPOB;
use App\Http\Controllers\xadm\RekapitulasiTA as x0RekapitulasiTA;
use App\Http\Controllers\xadm\PlottingSidang as x0PlottingSidang;
use App\Http\Controllers\xadm\KelolaPenilaian as x0KelolaPenilaian;
use App\Http\Controllers\xadm\DataPengguna as x0DataPengguna;

use App\Http\Controllers\xdos\Homepage as x1Homepage;
use App\Http\Controllers\xdos\PeriodeTA as x1PeriodeTA;
use App\Http\Controllers\xdos\inputTopik as x1InputTopik;
use App\Http\Controllers\xdos\DataMahasiswa as x1DataMahasiswa;
use App\Http\Controllers\xdos\AgendaSidang as x1AgendaSidang;

use App\Http\Controllers\xmhs\Homepage as x2Homepage;
use App\Http\Controllers\xmhs\PeriodeTA as x2PeriodeTA;
use App\Http\Controllers\xmhs\PilihanTopik as x2PilihanTopik;
use App\Http\Controllers\xmhs\TugasAkhir as x2TugasAkhir;
use App\Http\Controllers\xmhs\PendaftaranSidang as x2PendaftaranSidang;
use App\Http\Controllers\xmhs\JadwalSidang as x2JadwalSidang;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        switch ($user->type) {
            case 'admin':
                return redirect()->route('x0.Homepage');
            case 'dosen':
                return redirect()->route('x1.Homepage');
            case 'user':
                return redirect()->route('x2.Homepage');
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors(['type' => 'User not found.']);
        }
    }
    return view('auth.login');
});

Auth::routes();



/** ROUTES::ADMIN */
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    // ADMIN::HOMEPAGE
    Route::get('/admin/home/view', [x0Homepage::class, 'x0homeView'])->name('x0.Homepage');

    // ADMIN::PERIODE_TA
    Route::get('/admin/pta/view', [x0PeriodeTA::class, 'x0ptaView'])->name('x0.PeriodeTA');

    Route::get('/admin/pta/json1', [x0PeriodeTA::class, 'x0ptaData1'])->name('x0.PeriodeTA-json1');
    Route::get('/admin/pta/json2', [x0PeriodeTA::class, 'x0ptaData2'])->name('x0.PeriodeTA-json2');

    Route::post('/admin/pta/form1', [x0PeriodeTA::class, 'x0ptaNewPeriode'])->name('x0.PeriodeTA-form1');

    Route::put('/admin/pta/kuota/{id}', [x0PeriodeTA::class, 'x0ptaEditKuota']);
    Route::post('/admin/pta/close/{id}', [x0PeriodeTA::class, 'x0ptaClosePeriode']);
    Route::delete('/admin/pta/delete/{id}', [x0PeriodeTA::class, 'x0ptaDeletePeriode']);

    // ADMIN::KELOLA_POB
    Route::get('/admin/pob/view', [x0KelolaPOB::class, 'x0pobView'])->name('x0.KelolaPOB');

    // ADMIN::REKAPITULASI_TA
    Route::get('/admin/rta1/view', [x0RekapitulasiTA::class, 'x0rta1View'])->name('x0.RekapitulasiTA-1');
    Route::get('/admin/rta2/view', [x0RekapitulasiTA::class, 'x0rta2View'])->name('x0.RekapitulasiTA-2');
    Route::get('/admin/rta3/view', [x0RekapitulasiTA::class, 'x0rta3View'])->name('x0.RekapitulasiTA-3');

    Route::get('/admin/rta1/json1', [x0RekapitulasiTA::class, 'x0rta1Data1'])->name('x0.RekapitulasiTA1-json1');
    Route::get('/admin/rta1/json2', [x0RekapitulasiTA::class, 'x0rta1Data2'])->name('x0.RekapitulasiTA1-json2');
    
    Route::post('/admin/rta1/form1', [x0RekapitulasiTA::class, 'x0rta1AssignDos'])->name('x0.RekapitulasiTA1-form1');
    
    Route::get('/admin/rta2/json1', [x0RekapitulasiTA::class, 'x0rta2Data1'])->name('x0.RekapitulasiTA2-json1');
    
    Route::get('/admin/rta3/json1', [x0RekapitulasiTA::class, 'x0rta3Data1'])->name('x0.RekapitulasiTA3-json1');

    Route::get('/admin/rta3/download1/{id}', [x0RekapitulasiTA::class, 'x0rta3Download1'])->name('x0.RekapitulasiTA3-download1');
    Route::get('/admin/rta3/download2/{id}', [x0RekapitulasiTA::class, 'x0rta3Download2'])->name('x0.RekapitulasiTA3-download2');


    // ADMIN::PLOTTING_SIDANG
    Route::get('/admin/psd/view', [x0PlottingSidang::class, 'x0psdView'])->name('x0.PlottingSidang');

    Route::get('/admin/psd/json1', [x0PlottingSidang::class, 'x0psdData1'])->name('x0.PlottingSidang-json1');
    Route::get('/admin/psd/json2/{id}', [x0PlottingSidang::class, 'x0psdData2'])->name('x0.PlottingSidang-json2');

    Route::post('/admin/psd/form1', [x0PlottingSidang::class, 'x0psdPostReview'])->name('x0.PlottingSidang-form1');

    // ADMIN::KELOLA_PENILAIAN
    Route::get('/admin/kpn/view', [x0KelolaPenilaian::class, 'x0kpnView'])->name('x0.KelolaPenilaian');
    
    Route::get('/admin/kpn/json1', [x0KelolaPenilaian::class, 'x0kpnData1'])->name('x0.KelolaPenilaian-json1');
    Route::get('/admin/kpn/json2/{id}', [x0KelolaPenilaian::class, 'x0kpnData2'])->name('x0.KelolaPenilaian-json2');

    Route::post('/admin/kpn/form1', [x0KelolaPenilaian::class, 'x0kpnKonfirmasi'])->name('x0.KelolaPenilaian-form1');


    // ADMIN::DATA_PENGGUNA
    Route::get('/admin/dtp1/view', [x0DataPengguna::class, 'x0dtp1View'])->name('x0.DataPengguna-1');
    Route::get('/admin/dtp2/view', [x0DataPengguna::class, 'x0dtp2View'])->name('x0.DataPengguna-2');
    Route::get('/admin/dtp3/view', [x0DataPengguna::class, 'x0dtp3View'])->name('x0.DataPengguna-3');

    Route::get('/admin/dtp1/json1', [x0DataPengguna::class, 'x0dtp1Data1'])->name('x0.DataPengguna1-json1');
    Route::get('/admin/dtp2/json2', [x0DataPengguna::class, 'x0dtp2Data1'])->name('x0.DataPengguna2-json1');  
    Route::get('/admin/dtp3/json3', [x0DataPengguna::class, 'x0dtp3Data1'])->name('x0.DataPengguna3-json1');

    Route::post('/admin/dtp1/form1', [x0DataPengguna::class, 'x0dtp1TambahAdmin'])->name('x0.DataPengguna1-form1');
    Route::post('/dosen/dtp2/form1', [x0DataPengguna::class, 'x0dtp2TambahDosen'])->name('x0.DataPengguna2-form1');
    
    Route::delete('/admin/dtp/delete-user/{id}', [x0DataPengguna::class, 'x0dtpDeleteUser']);
    Route::post('/admin/dtp/reset-password/{id}', [x0DataPengguna::class, 'x0dtpResetPassword']);
});

/** ROUTES::DOSEN */
Route::middleware(['auth', 'user-access:dosen'])->group(function () {

    // DOSEN::HOMEPAGE
    Route::get('/dosen/home/view', [x1Homepage::class, 'x1homeView'])->name('x1.Homepage');

    // DOSEN::PERIODE_TA
    Route::get('/dosen/pta/view', [x1PeriodeTA::class, 'x1ptaView'])->name('x1.PeriodeTA');

    Route::get('/dosen/pta/json1', [x1PeriodeTA::class, 'x1ptaData1'])->name('x1.PeriodeTA-json1');

    // DOSEN::INPUT_TOPIK
    Route::get('/dosen/itp/view', [x1InputTopik::class, 'x1itpView'])->name('x1.TopikTA');

    Route::get('/dosen/itp/json1', [x1InputTopik::class, 'x1itpData1'])->name('x1.TopikTA-json1');

    Route::post('/dosen/itp/form1', [x1InputTopik::class, 'x1itpTambahTopik'])->name('x1.TopikTA-form1');
    
    Route::delete('/dosen/itp/delete/{id}', [x1InputTopik::class, 'x1itpHapusTopik']);

    // DOSEN::DATA_MAHASISWA
    Route::get('/dosen/dtm1/view', [x1DataMahasiswa::class, 'x1dtm1View'])->name('x1.DataMahasiswa-1');
    Route::get('/dosen/dtm2/view', [x1DataMahasiswa::class, 'x1dtm2View'])->name('x1.DataMahasiswa-2');
    Route::get('/dosen/dtm3/view', [x1DataMahasiswa::class, 'x1dtm3View'])->name('x1.DataMahasiswa-3');

    Route::get('/dosen/dtm1/json1', [x1DataMahasiswa::class, 'x1dtm1Data1'])->name('x1.DataMahasiswa1-json1');

    Route::post('/dosen/dtm1/accept/{id}', [x1DataMahasiswa::class, 'x1dtm1AcceptSubm']);
    Route::post('/dosen/dtm1/reject/{id}', [x1DataMahasiswa::class, 'x1dtm1RejectSubm']);
    Route::post('/dosen/dtm1/catatan/{id}', [x1DataMahasiswa::class, 'x1dtm1Catatan']);

    Route::get('/dosen/dtm2/json1', [x1DataMahasiswa::class, 'x1dtm2Data1'])->name('x1.DataMahasiswa2-json1');
    
    Route::get('/dosen/dtm3/json1', [x1DataMahasiswa::class, 'x1dtm3Data1'])->name('x1.DataMahasiswa3-json1');
    Route::get('/dosen/dtm3/json2/{id}', [x1DataMahasiswa::class, 'x1dtm3Data2'])->name('x1.DataMahasiswa3-json2');

    Route::post('/dosen/dtm3/form1', [x1DataMahasiswa::class, 'x1dtm3UploadNilai'])->name('x1.DataMahasiswa3-form1');



    // DOSEN::AGENDA_SIDANG
    Route::get('/dosen/asd/view', [x1AgendaSidang::class, 'x1asdView'])->name('x1.AgendaSidang');

    Route::get('/dosen/asd/json1', [x1AgendaSidang::class, 'x1asdData1'])->name('x1.AgendaSidang-json1');

});

/** ROUTES::USER */
Route::middleware(['auth', 'user-access:user'])->group(function () {

    // USER::HOMEPAGE
    Route::get('/user/home/view', [x2Homepage::class, 'x2homeView'])->name('x2.Homepage');

    // USER::PERIODE_TA
    Route::get('/user/pta/view', [x2PeriodeTA::class, 'x2ptaView'])->name('x2.PeriodeTA');
    Route::get('/user/pta/json1', [x2PeriodeTA::class, 'x2ptaData1'])->name('x2.PeriodeTA-json1');
    
    // USER::PILIHAN_TOPIK
    Route::get('/user/plt/view', [x2PilihanTopik::class, 'x2pltView'])->name('x2.TopikTA');
    Route::get('/user/plt/json1', [x2PilihanTopik::class, 'x2pltData1'])->name('x2.TopikTA-json1');

    // USER::TUGAS_AKHIR
    Route::get('/user/tak1/view', [x2TugasAkhir::class, 'x2tak1View'])->name('x2.TugasAkhir-1');
    Route::get('/user/tak2/view', [x2TugasAkhir::class, 'x2tak2View'])->name('x2.TugasAkhir-2');

    Route::get('/user/tak1/json1', [x2TugasAkhir::class, 'x2tak1Data1'])->name('x2.TugasAkhir1-json1');
    Route::post('/user/tak1/form1', [x2TugasAkhir::class, 'x2tak1SubmitProp'])->name('x2.TugasAkhir1-form1');

    Route::get('/user/tak2/json1', [x2TugasAkhir::class, 'x2tak2Data1'])->name('x2.TugasAkhir2-json1');

    // USER::PENDAFTARAN_SIDANG
    Route::get('/user/pds1/view', [x2PendaftaranSidang::class, 'x2pds1View'])->name('x2.PendaftaranSidang-1');
    Route::get('/user/pds2/view', [x2PendaftaranSidang::class, 'x2pds2View'])->name('x2.PendaftaranSidang-2');
    Route::get('/user/pds3/view', [x2PendaftaranSidang::class, 'x2pds3View'])->name('x2.PendaftaranSidang-3');
    Route::get('/user/pds4/view', [x2PendaftaranSidang::class, 'x2pds4View'])->name('x2.PendaftaranSidang-4');

    Route::get('/user/pds1/json1', [x2PendaftaranSidang::class, 'x2pds1Data1'])->name('x2.PendaftaranSidang1-json1');
    Route::post('/user/pds1/form1', [x2PendaftaranSidang::class, 'x2pds1RegProp'])->name('x2.PendaftaranSidang1-form1');

    Route::get('/user/pds2/json1', [x2PendaftaranSidang::class, 'x2pds2Data1'])->name('x2.PendaftaranSidang2-json1');
    Route::post('/user/pds2/form1', [x2PendaftaranSidang::class, 'x2pds2RegHas'])->name('x2.PendaftaranSidang2-form1');

    Route::get('/user/pds3/json1', [x2PendaftaranSidang::class, 'x2pds3Data1'])->name('x2.PendaftaranSidang3-json1');    
    Route::post('/user/pds3/form1', [x2PendaftaranSidang::class, 'x2pds3RegSid'])->name('x2.PendaftaranSidang3-form1');

    Route::get('/user/pds4/json1', [x2PendaftaranSidang::class, 'x2pds4Data1'])->name('x2.PendaftaranSidang4-json1');

    Route::get('/user/pds4/json2/{id}', [x2PendaftaranSidang::class, 'x2pds4Data2'])->name('x2.PendaftaranSidang4-json2');
    Route::post('/user/pds4/form1', [x2PendaftaranSidang::class, 'x2pds4RequestUbah'])->name('x2.PendaftaranSidang4-form1');

    // USER::JADWAL_SIDANG
    Route::get('/user/jsd/view', [x2JadwalSidang::class, 'x2jsdView'])->name('x2.JadwalSidang');

    Route::get('/user/jsd/json1', [x2JadwalSidang::class, 'x2jsdData1'])->name('x2.JadwalSidang-json1');

});
