<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    // Add 'image' to fillable
    protected $fillable = [
        'image',
        // add other columns if needed, e.g., 'title', 'subtitle'
    ];
}
