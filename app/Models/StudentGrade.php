<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    protected $table = 'student_grades';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'studentID',
        'firstName',
        'lastName',
        'middleName',
        'suffix',
        'gender',
        'schoolYearTitle',
        'courseID',
        'courseTitle',
        'yearLevel',
        'subjectCode',
        'subjectTitle',
        'units',
        'Faculty',
        'faculty_id',
        'Final_Rating',
        'Retake_Grade',
    ];

    /**
     * ✅ Relationship: Each grade belongs to one student
     */
    public function student()
    {
        // studentID in student_grades → studentID in students table
        return $this->belongsTo(Student::class, 'studentID', 'studentID');
    }

    /**
     * ✅ Relationship: Each grade belongs to one faculty
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }
}
