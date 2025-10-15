<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Hero;
use App\Models\Slider;
use App\Models\Event;

class UploadWelcomeAssets extends Command
{
    protected $signature = 'welcome:upload-assets';
    protected $description = 'Upload Hero, Sliders, and Events images and save relative paths to DB';

    public function handle()
    {
        $this->info('Uploading Hero, Sliders, and Events...');

        // HERO
        $heroPath = public_path('uploads/hero');
        if (!File::exists($heroPath)) {
            File::makeDirectory($heroPath, 0755, true);
        }
        $heroFile = 'images/psych-bg.jpg'; // replace with your local source path
        if (File::exists($heroFile)) {
            $filename = time().'_hero.'.pathinfo($heroFile, PATHINFO_EXTENSION);
            File::copy($heroFile, $heroPath.'/'.$filename);

            $hero = Hero::first() ?? new Hero();
            $hero->title = 'Bachelor of Arts in Psychology';
            $hero->subtitle = 'Discover knowledge, explore human behavior, and build your future in Psychology.';
            $hero->image = 'uploads/hero/'.$filename; // relative path
            $hero->save();
            $this->info('Hero uploaded!');
        }

        // SLIDERS
        $sliderPath = public_path('uploads/sliders');
        if (!File::exists($sliderPath)) {
            File::makeDirectory($sliderPath, 0755, true);
        }
        $sliderFiles = [
            'images/psych1.jpg',
            'images/psych2.jpg',
            'images/psych3.jpg',
        ];
        foreach ($sliderFiles as $file) {
            if (File::exists($file)) {
                $filename = time().'_slider_'.uniqid().'.'.pathinfo($file, PATHINFO_EXTENSION);
                File::copy($file, $sliderPath.'/'.$filename);

                $slider = new Slider();
                $slider->image = 'uploads/sliders/'.$filename;
                $slider->save();
                $this->info("Slider uploaded: $filename");
            }
        }

        // EVENTS
        $eventPath = public_path('uploads/events');
        if (!File::exists($eventPath)) {
            File::makeDirectory($eventPath, 0755, true);
        }
        $events = [
            [
                'title' => 'Psych Week 2025',
                'description' => 'A week-long celebration of psychology with seminars, workshops, and exhibits.',
                'image' => 'images/event1.jpg',
                'date' => now()->addDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Research Colloquium',
                'description' => 'Student-led research presentations showcasing the latest studies in psychology.',
                'image' => 'images/event2.jpg',
                'date' => now()->addDays(10)->format('Y-m-d'),
            ],
            [
                'title' => 'Mental Health Forum',
                'description' => 'An open discussion on mental health awareness and support systems.',
                'image' => 'images/event3.jpg',
                'date' => now()->addDays(15)->format('Y-m-d'),
            ],
        ];

        foreach ($events as $ev) {
            if (File::exists($ev['image'])) {
                $filename = time().'_event_'.uniqid().'.'.pathinfo($ev['image'], PATHINFO_EXTENSION);
                File::copy($ev['image'], $eventPath.'/'.$filename);

                $event = new Event();
                $event->title = $ev['title'];
                $event->description = $ev['description'];
                $event->event_date = $ev['date'];
                $event->image = 'uploads/events/'.$filename;
                $event->save();

                $this->info("Event uploaded: ".$ev['title']);
            }
        }

        $this->info('All assets uploaded successfully!');
    }
}
