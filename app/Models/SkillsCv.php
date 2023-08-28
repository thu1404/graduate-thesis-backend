<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillsCv extends Model
{
    use HasFactory;

    protected $table = 'skills_cv';

    protected $fillable = [
        'id',
        'cv_id',
        'skill_id',
        'num_lever',
        'created_at',
        'updated_at',
    ];
}
