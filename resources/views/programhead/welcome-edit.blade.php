<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-maroon">Edit Welcome Page</h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        :root { --maroon: #800000; --maroon-dark: #660000; --maroon-light: #fff0f0; }

        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f8f8; }

        .nav-tabs .nav-link.active {
            background-color: var(--maroon);
            color: #fff;
            border-color: var(--maroon) var(--maroon) transparent;
        }

        .container-card {
            background-color: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-top: 1rem;
        }

        .form-control { border-radius:0.5rem; margin-bottom:1rem; }
        .btn-primary { background-color: var(--maroon); border-radius:0.5rem; width:100%; }
        .btn-primary:hover { background-color: var(--maroon-dark); }

        .image-container {
            display:inline-block;
            position:relative;
            border:1px dashed #d1d5db;
            border-radius:0.5rem;
            padding:5px;
            margin-bottom:10px;
            text-align:center;
            cursor:pointer;
            transition: border 0.2s;
        }
        .image-container:hover { border-color: var(--maroon); }
        .preview-img, .slider-preview { max-width:200px; border-radius:0.5rem; display:block; margin:auto; }

        .replace-text { position:absolute; top:5px; left:5px; background:rgba(0,0,0,0.5); color:#fff; padding:2px 6px; font-size:0.7rem; border-radius:3px; }

        .event-form { background-color: var(--maroon-light); padding:1rem; border-radius:0.75rem; margin-bottom:1.5rem; }

        .file-name { font-size:0.8rem; color:#4b5563; text-align:center; display:block; margin-top:5px; }
    </style>

    <div class="container-card">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <ul class="nav nav-tabs mb-3" id="editTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button">Hero</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sliders-tab" data-bs-toggle="tab" data-bs-target="#sliders" type="button">Sliders</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button">Events</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button">About</button>
            </li>
        </ul>

        <form action="{{ route('welcome.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="tab-content" id="editTabsContent">

                {{-- HERO --}}
                <div class="tab-pane fade show active" id="hero">
                    <h4>Hero Section</h4>
                    <input type="text" name="title" placeholder="Hero Title" value="{{ $hero->title ?? '' }}" class="form-control">
                    <input type="text" name="subtitle" placeholder="Hero Subtitle" value="{{ $hero->subtitle ?? '' }}" class="form-control">

                    <div class="image-container" onclick="this.querySelector('input').click();">
                        @if(!empty($hero->image) && file_exists(public_path($hero->image)))
                            <img src="{{ asset($hero->image) }}" class="preview-img hero-img">
                            <span class="replace-text">Click to replace</span>
                        @else
                            <span class="replace-text">Click to upload image</span>
                        @endif
                        <input type="file" name="hero_image" style="display:none;" onchange="showFileName(this,'hero')">
                        <span id="heroFileName" class="file-name"></span>
                    </div>
                </div>

                {{-- SLIDERS --}}
                <div class="tab-pane fade" id="sliders">
                    <h4>Carousel Sliders</h4>
                    <input type="file" name="slider_images[]" multiple class="form-control mb-2" onchange="showFileName(this,'sliders')">
                    <span id="slidersFileName" class="file-name"></span>

                    <div class="d-flex flex-wrap gap-2">
                        @if(!empty($sliders))
                            @foreach($sliders as $slider)
                                <div class="image-container" onclick="this.querySelector('input').click();">
                                    <img src="{{ asset($slider->image) }}" class="slider-preview slider-img">
                                    <span class="replace-text">Click to replace</span>
                                    <input type="file" name="existing_slider_images[{{ $slider->id }}]" style="display:none;" onchange="showFileName(this,'slider{{ $slider->id }}')">
                                    <span id="slider{{ $slider->id }}FileName" class="file-name"></span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- EVENTS --}}
                <div class="tab-pane fade" id="events">
                    <h4>Events</h4>
                    <div class="event-form">
                        <h5>Add New Event</h5>
                        <input type="text" name="event_title" placeholder="Event Title" class="form-control">
                        <textarea name="event_description" placeholder="Event Description" class="form-control"></textarea>
                        <input type="date" name="event_date" class="form-control">
                        <input type="file" name="event_image" class="form-control mb-2" onchange="showFileName(this,'newEvent')">
                        <span id="newEventFileName" class="file-name"></span>
                    </div>

                    @if(!empty($events))
                        @foreach($events as $event)
                            <div class="event-form">
                                <h5>Edit Event: {{ $event->title }}</h5>
                                <input type="text" name="existing_event_title[{{ $event->id }}]" value="{{ $event->title }}" class="form-control">
                                <textarea name="existing_event_description[{{ $event->id }}]" class="form-control">{{ $event->description }}</textarea>
                                <input type="date" name="existing_event_date[{{ $event->id }}]" value="{{ $event->event_date }}" class="form-control">

                                <div class="image-container" onclick="this.querySelector('input').click();">
                                    @if(!empty($event->image) && file_exists(public_path($event->image)))
                                        <img src="{{ asset($event->image) }}" class="preview-img event-img">
                                        <span class="replace-text">Click to replace</span>
                                    @else
                                        <span class="replace-text">Click to upload image</span>
                                    @endif
                                    <input type="file" name="existing_event_image[{{ $event->id }}]" style="display:none;" onchange="showFileName(this,'event{{ $event->id }}')">
                                    <span id="event{{ $event->id }}FileName" class="file-name"></span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ABOUT --}}
                <div class="tab-pane fade" id="about">
                    <h4>About Section</h4>
                    <input type="text" name="about_title" placeholder="About Title" value="{{ $about->title ?? '' }}" class="form-control">
                    <textarea name="about_description" placeholder="About Description" class="form-control">{{ $about->description ?? '' }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Welcome Page</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showFileName(input, type){
            const fileName = input.files[0]?.name || '';
            const display = document.getElementById(type + 'FileName');
            if(display) display.textContent = fileName;
        }
    </script>
</x-app-layout>
