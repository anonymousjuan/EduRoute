<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email']; // add more if needed

    public function subjects() {
        return $this->belongsToMany(Subject::class, 'faculty_subjects', 'faculty_id', 'subject_id');
    }
}
