<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobs extends Model
{
    use HasFactory;
    use SoftDeletes;
    const inactive = 1;
    const active = 0;
    protected $table = 'jobs';
    protected $fillable = [
        'title',
        'requirements',
        'description',
        'benefit',
        'hiring_process',
        'location',
        'salary',
        'created_at',
        'updated_at',
        'user_id',
        'inactive',
        'gpa',
        'english'
    ];
    protected $hidden = [

        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function hiringProcess()
    {
        return $this->belongsTo('App\Models\HiringProcess', 'hiring_process', 'id');
    }


    public function jobApplicant()
    {
        return $this->hasMany('App\Models\JobsCV', 'job_id', 'id');
    }
    public function getCv()
    {
        return $this->belongsToMany(Cvs::class, 'job_cvs', 'job_id', 'cv_id')
            ->where('creator_id', auth()->user()->id)
            ->withPivot('round_id', 'status');
    }
    public function skill()
    {
        return $this->belongsToMany(Skills::class, 'skills_jobs', 'job_id', 'skill_id');
    }
    protected $appends = ['skills'];
    public function  getSkillsAttribute()
    {
        $skills = SkillsJobs::where('job_id', $this->id)->get();
        if ($skills) {
            $skills = $skills->map(function ($skill) {
                return $skill->skill;
            });
            return $skills;
        }
        return [];
    }
}
