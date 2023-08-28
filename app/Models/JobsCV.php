<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobsCV extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'job_cvs';
    const rejected = 0;
    const OnProcess = 1;
    protected $fillable = [
        'id',
        'job_id',
        'cv_id',
        'round_id',
        'status',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'status_name'
    ];

    public function getStatusNameAttribute()
    {
        if ($this->status == $this::rejected) {
            return 'Rejected';
        } else {
            return 'On process';
        }
    }
    //orderby status desc


    public function job()
    {
        return $this->belongsTo('App\Models\Jobs', 'job_id', 'id');
    }

    public function cv()
    {
        return $this->belongsTo('App\Models\CVs', 'cv_id', 'id');
    }

    public function round()
    {

        if ($this->round_id == null) {
            return 'Has rejected';
        }
        return $this->belongsTo('App\Models\HiringProcessRound', 'round_id', 'id');
    }
}
