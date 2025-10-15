<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Slider;
use App\Models\Event;
use App\Models\About;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page (public view)
     */
    public function index()
    {
        $hero = Hero::first();
        $sliders = Slider::all();
        $events = Event::orderBy('event_date', 'asc')->get();
        $about = About::first();

        return view('welcome', compact('hero', 'sliders', 'events', 'about'));
    }

    /**
     * Show the edit form for the welcome page
     */
    public function edit()
    {
        $hero = Hero::first();
        $sliders = Slider::all();
        $events = Event::orderBy('event_date', 'asc')->get();
        $about = About::first();

        return view('programhead.welcome-edit', compact('hero', 'sliders', 'events', 'about'));
    }

    /**
     * Update the welcome page content
     */
    public function update(Request $request)
    {
        // === HERO ===
        $hero = Hero::first() ?? new Hero();
        $hero->title = $request->title;
        $hero->subtitle = $request->subtitle;

        // Replace hero image if uploaded
        if ($request->hasFile('hero_image')) {
            if ($hero->image && file_exists(public_path($hero->image))) {
                unlink(public_path($hero->image));
            }
            $file = $request->file('hero_image');
            $filename = time() . '_hero.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/hero'), $filename);
            $hero->image = 'uploads/hero/' . $filename;
        }

        $hero->save();

        // === SLIDERS ===
        // Add new slider images
        if ($request->hasFile('slider_images')) {
            foreach ($request->file('slider_images') as $file) {
                $slider = new Slider();
                $filename = time() . '_slider_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/sliders'), $filename);
                $slider->image = 'uploads/sliders/' . $filename;
                $slider->save();
            }
        }

        // Update existing slider images if replaced
        foreach ($request->existing_slider_images ?? [] as $id => $file) {
            $slider = Slider::find($id);
            if ($slider && $file) {
                if ($slider->image && file_exists(public_path($slider->image))) {
                    unlink(public_path($slider->image));
                }
                $filename = time() . '_slider_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/sliders'), $filename);
                $slider->image = 'uploads/sliders/' . $filename;
                $slider->save();
            }
        }

        // === EVENTS ===
        // Add new event
        if ($request->event_title) {
            $event = new Event();
            $event->title = $request->event_title;
            $event->description = $request->event_description;
            $event->event_date = $request->event_date;

            if ($request->hasFile('event_image')) {
                $file = $request->file('event_image');
                $filename = time() . '_event_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/events'), $filename);
                $event->image = 'uploads/events/' . $filename;
            }
            $event->save();
        }

        // Update existing events
        foreach ($request->existing_event_title ?? [] as $id => $title) {
            $event = Event::find($id);
            if ($event) {
                $event->title = $title;
                $event->description = $request->existing_event_description[$id] ?? $event->description;
                $event->event_date = $request->existing_event_date[$id] ?? $event->event_date;

                // Replace image only if uploaded
                if ($request->hasFile('existing_event_image') && isset($request->existing_event_image[$id])) {
                    if ($event->image && file_exists(public_path($event->image))) {
                        unlink(public_path($event->image));
                    }
                    $file = $request->file('existing_event_image')[$id];
                    $filename = time() . '_event_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/events'), $filename);
                    $event->image = 'uploads/events/' . $filename;
                }

                $event->save();
            }
        }

        // === ABOUT ===
        $about = About::first() ?? new About();
        $about->title = $request->about_title;
        $about->description = $request->about_description;
        $about->save();

        return back()->with('success', 'Welcome page updated successfully!');
    }

    /**
     * Delete a slider image and remove from DB
     */
    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image && file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }
        $slider->delete(); // remove from database
        return response()->json(['success' => true]);
    }

    /**
     * Delete the hero image
     */
    public function deleteHero($id)
    {
        $hero = Hero::findOrFail($id);
        if ($hero->image && file_exists(public_path($hero->image))) {
            unlink(public_path($hero->image));
        }
        $hero->image = null;
        $hero->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete an event image
     */
    public function deleteEventImage($id)
    {
        $event = Event::findOrFail($id);
        if ($event->image && file_exists(public_path($event->image))) {
            unlink(public_path($event->image));
        }
        $event->image = null;
        $event->save();

        return response()->json(['success' => true]);
    }
    public function showEvents()
{
    $today = Carbon::today();

    // Assuming $events is fetched from the database
    $events = Event::where('event_date', '>=', $today->subDays(3))
                   ->orderBy('event_date', 'asc')
                   ->get();

    return view('yourview', compact('events'));
}
}
