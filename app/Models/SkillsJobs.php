<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillsJobs extends Model
{
    use HasFactory;

    protected $table = 'skills_jobs';

    protected $fillable = [
        'id',
        'job_id',
        'skill_id',
        'created_at',
        'updated_at',
    ];

    public function skill()
    {
        return $this->belongsTo(Skills::class, 'skill_id', 'id');
    }
}
