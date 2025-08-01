<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SidangSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        /** Data MHS */
        'tipe_pengajuan',
        'tipe_sidang',
        'jadwal_sidang',
        'waktu_sidang', 
        'user_id',
        'judul',
        'dosen_id',
        'dosen2_id',
        'topik_id',

        /** Data ADM */
        'penguji_id', 
        'penguji2_id',
        'lokasi_sidang',
        'skema_sidang',
        'hasil', 
        'link_sidang',
        
        'status_sidang',

        /** MHS Files */
        'fsp1_pendaftaran',
        'fsp2_logbook',
        'fsp3_draft',
        'fsp4_nilai',

        'fsh1_pendaftaran',
        'fsh2_logbook',
        'fsh3_draft',
        'fsh4_nilai',

        'fsu1_buku',
        'fsu2_logbook',
        'fsu3_ba',
        'fsu4_pengesahan',
        'fsu5_nilai',

    ];

    public static function getTableColumns() // Fix ordering
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function dosen2()
    {
        return $this->belongsTo(User::class, 'dosen2_id'); // Tadinya dosen_id aja
    }

    public function penguji()
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(User::class, 'penguji2_id');

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topik()
    {
        return $this->belongsTo(Topic::class, 'topik_id');
    }

}
