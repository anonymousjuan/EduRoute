<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'student_grades'; // change this if your table name is 'grades'
    protected $primaryKey = 'id'; // default is 'id', so this is optional

    protected $fillable = [
        'studentID',
        'firstName',
        'lastName',
        'middleName',
        'suffix',
        'gender',
        'schoolYearTitle',
        'courseID',
        'Final_Rating',
        'courseTitle',
        'yearLevel',
    ];
}
