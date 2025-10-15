<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacultySubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'subject_id',
        'school_year_title',   // e.g. "2024–2025"
        'year_level',          // optional field to indicate which student year this subject belongs to
    ];

    /**
     * 🔹 Each FacultySubject belongs to a Faculty.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * 🔹 Each FacultySubject corresponds to one Subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * 🔹 Get all student grades related to this faculty-subject pairing.
     */
    public function grades()
    {
        return $this->hasMany(StudentGrade::class, 'faculty_subject_id');
    }

    /**
     * 🔹 Optionally link to a Curriculum (if each subject exists under a curriculum).
     */
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'subject_id', 'id');
    }

    /**
     * 🔹 A helper to get the school year in readable format.
     */
public function getFormattedSchoolYearAttribute()
{
    return $this->school_year_title ? str_replace('-', '–', $this->school_year_title) : null;
}

}
