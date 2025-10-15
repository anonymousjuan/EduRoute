<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorSubject extends Model
{
    protected $table = 'instructor_subject';
    protected $fillable = [
        'instructor_id',
        'subject_id',
        'status',
        'term',
        'school_year',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
