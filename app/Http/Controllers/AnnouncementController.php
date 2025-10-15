<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Mail\MyAnnouncementMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements (limit to 5 latest, only within 30 days).
     */
    public function index()
    {
        // Show only announcements created within the last 30 days
        $announcements = Announcement::where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->take(5)
            ->get();

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
{
    // Current time in Philippine Time
    $currentTime = \Carbon\Carbon::now('Asia/Manila')->format('M d, Y h:i A');
    return view('announcements.create', compact('currentTime'));
}

    /**
     * Store a newly created announcement and send emails to all users.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Explicitly set created_at to current time (Carbon now)
        $announcement = Announcement::create([
            'title'      => $request->title,
            'message'    => $request->message,
            'created_at' => Carbon::now(),  // ← current timestamp
            'updated_at' => Carbon::now(),
        ]);

        // Gmail accounts to send from (currently only 1)
        $fromEmails = [
            'romajavier0726@gmail.com',
        ];

        // Send announcement to all users
        $users = User::all();
        foreach ($users as $user) {
            $fromEmail = $fromEmails[array_rand($fromEmails)];
            Mail::to($user->email)->send(new MyAnnouncementMail($fromEmail, $announcement));
        }

        return redirect()->route('announcements.index')
                         ->with('success', 'Announcement sent successfully!');
    }
}
