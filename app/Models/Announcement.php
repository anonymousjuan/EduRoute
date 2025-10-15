<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'message'];
    public $timestamps = true; // Laravel will handle created_at & updated_at
}
