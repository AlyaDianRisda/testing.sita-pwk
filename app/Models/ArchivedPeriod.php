<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedPeriod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'file_path',
        'file_path2',
        'start_date',
        'end_date',
        'archived_at',
    ];
}
