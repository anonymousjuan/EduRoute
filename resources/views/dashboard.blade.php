<!-- Font Awesome 6 CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<aside class="sidebar">
    <nav>
        @php
            $role = trim(strtolower(auth()->user()->role));
        @endphp

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
            <script>
                setTimeout(() => { window.location.reload(); }, 1000);
            </script>
        @endif

        {{-- Sidebar Links --}}
        @if ($role === 'instructor')
            <a href="{{ route('grades.index') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Grades</a>
            <a href="{{ route('enrolled.index') }}" class="nav-link"><i class="fa-solid fa-user-graduate"></i> Enrolled Students</a>
            <a href="{{ route('faculty.subjects') }}" class="nav-link"><i class="fa-solid fa-pencil"></i> Faculty Subjects</a>
            
    <a href="{{ route('my-subjects.index') }}" class="nav-link"><i class="fa-solid fa-book-open"></i> My Subjects</a>
        @elseif ($role === 'programhead')
            <a href="{{ route('curriculum.index') }}" class="nav-link"><i class="fa-solid fa-book"></i> Curriculum</a>
            <a href="{{ route('students.index') }}" class="nav-link"><i class="fa-solid fa-user-graduate"></i> Students</a>
            <a href="{{ route('grades.index') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Grades</a>
            <a href="{{ route('faculty.subjects') }}" class="nav-link"><i class="fa-solid fa-pencil"></i> Faculty Subjects</a>
            <a href="{{ route('announcements.index') }}" class="nav-link"><i class="fa-solid fa-bullhorn"></i> Announcements</a>
            <a href="{{ route('welcome.edit') }}" class="nav-link"><i class="fa-solid fa-house"></i> Welcome Page</a>
        @elseif ($role === 'dean')
            <a href="{{ route('curriculum.index') }}" class="nav-link"><i class="fa-solid fa-book"></i> Curriculum</a>
            <a href="{{ route('students.index') }}" class="nav-link"><i class="fa-solid fa-user-graduate"></i> Students</a>
        @else
            <a href="{{ route('curriculum.index') }}" class="nav-link"><i class="fa-solid fa-book"></i> Curriculum</a>
            <a href="{{ route('students.index') }}" class="nav-link"><i class="fa-solid fa-user-graduate"></i> Students</a>
            <a href="{{ route('grades.index') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Grades</a>
            <a href="{{ route('enrolled.index') }}" class="nav-link"><i class="fa-solid fa-user-graduate"></i> Enrolled Students</a>
            <a href="{{ route('accounts.index') }}" class="nav-link"><i class="fa-solid fa-gear"></i> Accounts</a>
        @endif

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
        </form>
    </nav>
</aside>

<main class="main-content">
    {{-- Logo Section --}}
    <div class="logo-section">
        <img src="{{ asset('images/bap.jpg') }}" alt="School Logo" class="logo">
        <h1 class="school-name">Bachelor of Arts in Psychology</h1>
    </div>

    {{-- Vision & Mission Section (Side by Side Boxes) --}}
    <section class="vision-mission-container">
        <div class="vision-box">
            <h2>Our Vision</h2>
            <p>UIP 2030: A Smart, globally competitive, and responsive state university.</p>
        </div>
        <div class="mission-box">
            <h2>Our Mission</h2>
            <p>
                UIP is committed to producing globally competent and virtuous human resources,
                generating advanced knowledge, and developing innovative technologies for the sustainable
                development of society.
            </p>
        </div>
    </section>

    {{-- Announcements --}}
    @php
        use Carbon\Carbon;

        $oneHourAgo = Carbon::now()->subHour();
        $latestAnnouncements = \App\Models\Announcement::where('created_at', '>=', $oneHourAgo)
                                ->latest()
                                ->take(3)
                                ->get();
    @endphp 

    <div class="announcements-section mt-5">
        <h3 class="announcements-title">📢 Latest Announcements</h3>
        <div class="row g-3">
            @forelse($latestAnnouncements as $ann)
                <div class="col-md-4">
                    <div class="announcement-card">
                        <h5>{{ $ann->title }}</h5>
                        <p>{{ \Illuminate\Support\Str::limit($ann->message, 80) }}</p>
                        <span class="announcement-date">{{ $ann->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-muted">No announcements yet.</p>
            @endforelse
        </div>
    </div>
</main>

{{-- Styles --}}
<style>
body {
    display: flex;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f4f9;
}

.sidebar {
    width: 18rem;
    background-color: #800000;
    color: #fff;
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    position: fixed;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 1rem;
    color: #f0f0f0;
    border-radius: 12px;
    text-decoration: none;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}
.nav-link:hover { background-color: #a83232; transform: translateX(6px); }

.logout-button {
    margin-top: auto;
    background-color: transparent;
    border: none;
    width: 100%;
    padding: 12px 20px;
    font-weight: 700;
    font-size: 1rem;
    color: #ff6b6b;
    cursor: pointer;
    border-radius: 12px;
    text-align: left;
    transition: all 0.3s ease;
}
.logout-button:hover { background-color: #ff6b6b; color: #fff; transform: translateX(4px); }

.main-content {
    flex: 1;
    margin-left: 18rem;
    padding: 3rem 4rem;
}

.logo-section { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; }
.logo { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
.school-name { font-size: 2rem; font-weight: 700; color: #800000; }

.vision-mission-container {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}
.vision-box, .mission-box {
    flex: 1 1 48%;
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 6px 15px rgba(128,0,0,0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.vision-box:hover, .mission-box:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(128,0,0,0.25); }
.vision-box h2, .mission-box h2 { color: #800000; margin-bottom: 1rem; }

.announcements-title { font-size: 1.5rem; font-weight: 700; color: #800000; margin-bottom: 1rem; }
.announcement-card {
    background: #fff; border-radius: 12px; padding: 1.2rem;
    box-shadow: 0 4px 15px rgba(128,0,0,0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.announcement-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(128,0,0,0.25); }
.announcement-card h5 { color: #800000; margin-bottom: 0.5rem; }
.announcement-card p { color: #333; margin-bottom: 0.5rem; }
.announcement-date { font-size: 0.8rem; color: #999; }

@media (max-width: 768px) {
    body { flex-direction: column; }
    .sidebar { width: 100%; flex-direction: row; padding: 1rem; position: relative; }
    .nav-link { flex: 0 0 auto; margin-right: 0.5rem; }
    .main-content { margin-left: 0; padding: 2rem 1.5rem; }
    .logo-section { flex-direction: column; align-items: flex-start; }
    .logo { width: 100px; height: 100px; }
    .school-name { font-size: 1.5rem; }
    .vision-box, .mission-box { flex: 1 1 100%; }
}
</style>
