<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BimbinganData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dosen_id',
        'dosen2_id',
        'topik_id',
        'judul',
        'status_bimbingan',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    // Perbaikan penamaan fungsi dosen di ProposalSubmission model.
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
    
    public function dosen2()
    {
        return $this->belongsTo(User::class, 'dosen2_id');
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
