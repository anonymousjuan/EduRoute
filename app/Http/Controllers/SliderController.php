<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider; // Make sure your Slider model is here

class SliderController extends Controller
{
    /**
     * Remove the specified slider from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Slider $slider)
    {
        // Delete the image file if it exists
        if (file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        // Delete the slider record from DB
        $slider->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Slider deleted successfully.');
    }
}
