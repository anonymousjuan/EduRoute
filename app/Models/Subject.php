<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'title', 'units', 'curriculum_id'
    ];

    public function faculties() {
        return $this->belongsToMany(Faculty::class, 'faculty_subjects', 'subject_id', 'faculty_id');
    }
}
