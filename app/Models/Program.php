<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    // ✅ table name (if your migration created `programs` table)
    protected $table = 'programs';

    // ✅ allow mass assignment
    protected $fillable = [
        'code',      // e.g., BAP
        'name',      // e.g., Bachelor of Arts in Psychology
        'description',
    ];

    // ✅ relationship: a Program has many Curriculums
    public function curriculums()
    {
        return $this->hasMany(Curriculum::class);
    }
}
