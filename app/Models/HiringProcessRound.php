<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiringProcessRound extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'hiring_process_rounds';

    protected $fillable = [
        'id',
        'name',
        'process_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    //hiđde
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
