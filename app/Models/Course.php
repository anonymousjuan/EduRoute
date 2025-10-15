<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Specify which columns can be filled via mass assignment
    protected $fillable = ['courseID', 'courseTitle'];
}
