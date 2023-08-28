<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferredPosition extends Model
{
    use HasFactory;

    protected $table = 'preferred_position';

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',

    ];
}
