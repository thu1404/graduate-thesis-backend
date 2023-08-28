<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiringProcess extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'hiring_processes';

    protected $fillable = [
        'id',
        'name',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //hidden
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //has many hiring process round
    public function hiringProcessRound()
    {
        return $this->hasMany(HiringProcessRound::class, 'process_id', 'id');
    }
}
