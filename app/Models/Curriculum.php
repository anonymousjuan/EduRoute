<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curriculums';

    protected $fillable = [
        'course_no',
        'descriptive_title',
        'units',
        'lec',
        'lab',
        'prerequisite',
        'year_level',
        'semester',
        'year_of_implementation'
    ];

}