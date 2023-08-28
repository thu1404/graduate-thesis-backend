<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cvs extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cvs';

    protected $fillable = [
        'id',
        'creator_id',
        'name',
        'avatar',
        'age',
        'gender_id',
        'phone',
        'email',
        'address',
        'position',
        'education',
        'experience',
        'skills',
        'cv_files',
        'gpa',
        'english'
    ];
    //hidden
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    protected $appends = [
        'avatar_link',
        'cv_files_link'
    ];
    public function getSkills()
    {
        return $this->belongsToMany(Skills::class, 'skills_cv', 'cv_id', 'id');
    }
    public function getAvatarLinkAttribute()
    {
        return $this->avatar ? env('APP_URL') . $this->avatar : '';
    }

    public function getCvFilesLinkAttribute()
    {
        return $this->cv_files ? env('APP_URL') . $this->cv_files : '';
    }


    public function getJob()
    {
        return $this->belongsToMany(Jobs::class, 'job_cvs', 'cv_id', 'job_id')->withPivot('round_id', 'status');
    }
}
