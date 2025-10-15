<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        
        $admin = User::where('email', 'superadmin@gmail.com')->first();

        if ($admin) {
            Announcement::firstOrCreate([
                'user_id' => $admin->id,
                'title' => 'Welcome to EduRoute!',
                'content' => 'This is a test announcement from the Super Admin.',
                'audience' => 'all',
                'published_at' => now(),
            ]);
        }
    }
}

