<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'student_grades';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
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
        'faculty_id', // ✅ for foreign key link to faculties table
    ];

    /**
     * Relationship: A student has many grades
     * Connects studentID (in students) → studentID (in student_grades)
     */
    public function grades()
    {
        return $this->hasMany(StudentGrade::class, 'studentID', 'studentID');
    }

    /**
     * Relationship: A student belongs to one faculty
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
