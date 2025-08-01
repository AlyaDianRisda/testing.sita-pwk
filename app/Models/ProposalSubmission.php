<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProposalSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dosen_id',
        'dosen2_id',
        'topik_id',
        'rancangan_judul',
        'draft_file_path',
        'status_pengajuan',
        'catatan_validasi',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    public function dos_utama()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function dos_kedua()
    {
        return $this->belongsTo(User::class, 'dosen2_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topik()
    {
        return $this->belongsTo(Topic::class);
    }

}
