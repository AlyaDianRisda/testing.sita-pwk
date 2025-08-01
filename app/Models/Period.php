<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Period extends Model
{
    protected $fillable = ['name', 'type', 'start_date', 'end_date', 'is_open'];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function kuotaBimbingan()
    {
        return $this->hasMany(ProposalKuotaDosen::class, 'period_id');
    }


}
