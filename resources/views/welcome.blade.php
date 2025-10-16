<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bachelor of Arts in Psychology</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #fdfdfd;
      color: #333;
    }

    /* Navbar */
    .navbar {
      background-color: #800000 !important;
    }
    .navbar .btn-outline-light {
      border-color: #fff;
      color: #fff;
      transition: all 0.3s ease;
    }
    .navbar .btn-outline-light:hover {
      background-color: #fff;
      color: #800000;
    }
    .navbar .btn-primary {
      background-color: #fff;
      color: #800000;
      border: 2px solid #fff;
      transition: all 0.3s ease;
    }
    .navbar .btn-primary:hover {
      background-color: #f0f0f0;
      color: #800000;
    }
    .navbar-brand img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* Hero Section */
    .hero {
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
      overflow: hidden;
      border-bottom-left-radius: 50px;
      border-bottom-right-radius: 50px;
      min-height: 50vh; /* Reduced height */
      max-height: 70vh; /* Limit max height */
      background-repeat: no-repeat;
      background-position: center;
      background-size: contain; /* Make image fully visible */
      background-color: #800000; /* fallback color */
    }
    .hero::after {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(128,0,0,0.5);
    }
    .hero-content {
      position: relative;
      z-index: 2;
      padding: 0 1rem;
    }
    .hero h1 {
      font-size: 2.5rem;
      font-weight: 700;
      text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
    }
    .hero p {
      font-size: 1.2rem;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
    }
    .hero .btn-primary {
      background-color: #fff;
      color: #800000;
      font-weight: 600;
    }
    .hero .btn-primary:hover {
      background-color: #f0f0f0;
      color: #660000;
    }

    /* Carousel */
    .carousel-item img {
      width: 100%;
      height: auto;
      object-fit: contain;
      border-radius: 15px;
    }
    .carousel-indicators button {
      background-color: #800000;
    }

    /* Section Titles */
    .section-title {
      margin: 50px 0 20px;
      text-align: center;
      color: #800000;
      font-weight: 700;
      font-size: 2rem;
      position: relative;
    }
    .section-title::after {
      content: "";
      width: 80px;
      height: 3px;
      background: #800000;
      display: block;
      margin: 10px auto 0;
      border-radius: 2px;
    }

    /* Event Cards */
    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(128,0,0,0.25);
    }
    .card-title {
      color: #800000;
      font-weight: 600;
    }
    .btn-primary {
      background-color: #800000;
      border: none;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #660000;
    }

    /* About Section */
    #about p {
      font-size: 1.1rem;
      line-height: 1.8;
    }

    /* Footer */
    footer {
      background: #800000;
      color: #fff;
      text-align: center;
      padding: 20px 0;
    }

    @media (max-width: 768px) {
      .hero h1 { font-size: 2rem; }
      .hero p { font-size: 1rem; }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="{{ asset('images/bap.jpg') }}" alt="Logo">
    </a>
    <div class="ms-auto">
      @auth
        @php $role = Auth::user()->role ?? 'user'; @endphp
        @if($role === 'admin')
          <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-light me-2"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
        @elseif($role === 'programhead')
          <a href="{{ url('/programhead/dashboard') }}" class="btn btn-outline-light me-2"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
        @elseif($role === 'faculty')
          <a href="{{ url('/faculty/dashboard') }}" class="btn btn-outline-light me-2"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
        @else
          <a href="{{ url('/dashboard') }}" class="btn btn-outline-light me-2"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
        @endif
      @else
        <a href="{{ route('login') }}" class="btn btn-outline-light me-2"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Register</a>
        @endif
      @endauth
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero" style="background-image: url('{{ $hero->image ? asset($hero->image) : asset('images/psych-bg.jpg') }}');">
  <div class="hero-content">
    <h1>{{ $hero->title ?? 'Bachelor of Arts in Mostoles' }}</h1>
    <p>{{ $hero->subtitle ?? 'Discover knowledge, explore human behavior, and build your future in Psychology.' }}</p>
    <a href="#about" class="btn btn-primary btn-lg"><i class="fa-solid fa-arrow-down"></i> Learn More</a>
  </div>
</section>

<!-- Carousel Slider -->
@if(!empty($sliders) && count($sliders) > 0)
<div id="carouselExampleIndicators" class="carousel slide container mt-5" data-bs-ride="carousel">
  <div class="carousel-indicators">
    @foreach($sliders as $key => $slider)
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
    @endforeach
  </div>
  <div class="carousel-inner rounded shadow">
    @foreach($sliders as $key => $slider)
      <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
        <img src="{{ asset($slider->image) }}" class="d-block w-100 img-fluid rounded" alt="Slider {{ $key+1 }}">
      </div>
    @endforeach
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
@endif

<!-- Events Section -->
<!-- Events Section -->
<section id="events" class="container mt-5">
  <h2 class="section-title">Upcoming Events</h2>
  <div class="row g-4">
    @foreach($events as $event)
      <div class="col-md-4">
        <div class="card shadow" data-event-date="{{ $event->event_date }}">
          <img src="{{ $event->image ? asset($event->image) : asset('images/default-event.jpg') }}" class="card-img-top img-fluid rounded" alt="{{ $event->title }}">
          <div class="card-body">
            <h5 class="card-title"><i class="fa-solid fa-calendar-day"></i> {{ $event->title }}</h5>
            <button class="btn btn-primary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#eventDesc{{ $event->id }}" aria-expanded="false" aria-controls="eventDesc{{ $event->id }}">
              <i class="fa-solid fa-eye"></i> Read More
            </button>
            <div class="collapse mt-2" id="eventDesc{{ $event->id }}">
              <p>{{ $event->description }}</p>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

<!-- Footer -->
<footer>
  <p>&copy; 2025 BA Psychology Community. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('#events .card');
    const now = new Date();

    cards.forEach(card => {
      const eventDateStr = card.getAttribute('data-event-date');
      if (!eventDateStr) return;

      const eventDate = new Date(eventDateStr);
      const diffTime = now - eventDate;
      const diffMinutes = diffTime / (1000 * 60); // convert ms to minutes

      if (diffMinutes > 10) {
        card.style.display = 'none';
      }
    });
  });
</script>
</body>
</html>
