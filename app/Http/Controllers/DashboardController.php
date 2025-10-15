<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();

        return view('dashboard', compact('announcements'));
    }
}
