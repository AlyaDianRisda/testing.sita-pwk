<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProposalKuotaDosen extends Model
{

    protected $fillable = [
        'period_id',
        'dosen_id',
        'kuota_total',
        'kuota_tersisa',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }
}
