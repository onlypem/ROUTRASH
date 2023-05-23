<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jarak extends Model
{
    use HasFactory;
    protected $table = 'jaraks';
    protected $fillable = [
        'loc_1',
        'loc_2',
        'distance',
    ];
}
