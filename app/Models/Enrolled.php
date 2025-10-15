<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolled extends Model
{
    use HasFactory;

    protected $table = 'enrolled';

    protected $fillable = [
        'name',
        'email',
        'course',
    ];
}
