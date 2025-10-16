<!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .nav-icon {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    .nav-item:hover .nav-icon {
        transform: scale(1.1);
        color: #FADFCD;
    }
    /* Floating animation for cards */
    .float-card {
        transition: all 0.4s ease;
    }
    .float-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    /* Gradient Text for titles */
    .gradient-text {
        background: linear-gradient(90deg, #800000, #A45D83, #F7CDE9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<aside class="fixed w-72 bg-gradient-to-b from-[#800000] via-[#a83232] to-[#5a0000] text-white h-screen flex flex-col p-6 shadow-2xl rounded-r-3xl">
    <nav class="flex flex-col h-full space-y-3">
        @php
            $role = trim(strtolower(auth()->user()->role ?? ''));
        @endphp

        @if(session('success'))
            <div class="bg-green-600/90 text-white font-semibold py-2 px-3 rounded-lg mb-3 animate-pulse text-center shadow-md backdrop-blur-sm">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(() => { window.location.reload(); }, 1000);
            </script>
        @endif

        <!-- App Header -->
        <div class="mb-8 text-center">
            <div class="w-20 h-20 mx-auto bg-white/10 rounded-full flex items-center justify-center mb-3 shadow-md">
                <i class="fa-solid fa-brain text-4xl text-[#FADFCD]"></i>
            </div>
            <h1 class="text-xl font-bold tracking-wide">EduRoute</h1>
            <p class="text-sm opacity-80 capitalize">{{ $role }}</p>
        </div>

        {{-- Sidebar Links --}}
        @if ($role === 'instructor')
            <a href="{{ route('grades.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-chart-line nav-icon"></i> Grades
            </a>
            <a href="{{ route('enrolled.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-user-graduate nav-icon"></i> Enrolled Students
            </a>
            <a href="{{ route('faculty.subjects') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-pencil nav-icon"></i> Faculty Subjects
            </a>
            <a href="{{ route('my-subjects.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-book-open nav-icon"></i> My Subjects
            </a>

        @elseif ($role === 'programhead')
            <a href="{{ route('curriculum.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-book nav-icon"></i> Curriculum
            </a>
            <a href="{{ route('students.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-user-graduate nav-icon"></i> Students
            </a>
            <a href="{{ route('grades.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-chart-line nav-icon"></i> Grades
            </a>
            <a href="{{ route('faculty.subjects') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-pencil nav-icon"></i> Faculty Subjects
            </a>
            <a href="{{ route('announcements.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-bullhorn nav-icon"></i> Announcements
            </a>
            <a href="{{ route('welcome.edit') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-house nav-icon"></i> Welcome Page
            </a>

        @elseif ($role === 'dean')
            <a href="{{ route('curriculum.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-book nav-icon"></i> Curriculum
            </a>
            <a href="{{ route('students.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-300 font-semibold">
                <i class="fa-solid fa-user-graduate nav-icon"></i> Students
            </a>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 rounded-xl text-[#ff6b6b] hover:bg-[#ff6b6b] hover:text-white transition-all duration-300 font-semibold">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </nav>
</aside>

<!-- ================= MAIN CONTENT ================= -->
<main class="ml-72 bg-gradient-to-b from-[#fff7f9] via-[#fdf5f9] to-[#fafafa] min-h-screen p-10 transition-all duration-500">

    <!-- HEADER -->
    <header class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 border-b border-gray-300/50 pb-6">
        <div class="flex items-center gap-6 mb-6 md:mb-0">
            <img src="{{ asset('images/bap.jpg') }}" alt="School Logo" class="w-28 h-28 rounded-full object-cover shadow-lg ring-4 ring-[#a83232]/40">
            <div>
                <h1 class="text-3xl font-extrabold gradient-text">Bachelor of Arts in Psychology</h1>
                <p class="text-gray-600 italic">Empowering minds. Building futures.</p>
            </div>
        </div>
    </header>

    <!-- Vision and Mission -->
    <section class="grid md:grid-cols-2 gap-8 mb-12">
        <div class="float-card bg-white/70 backdrop-blur-lg rounded-3xl p-8 shadow-lg border border-gray-200/60">
            <h2 class="text-2xl font-bold text-[#800000] mb-4 flex items-center gap-2"><i class="fa-solid fa-eye"></i> Our Vision</h2>
            <p class="text-gray-700 leading-relaxed">UIP 2030: A Smart, globally competitive, and responsive state university.</p>
        </div>
        <div class="float-card bg-white/70 backdrop-blur-lg rounded-3xl p-8 shadow-lg border border-gray-200/60">
            <h2 class="text-2xl font-bold text-[#800000] mb-4 flex items-center gap-2"><i class="fa-solid fa-bullseye"></i> Our Mission</h2>
            <p class="text-gray-700 leading-relaxed">
                UIP is committed to producing globally competent and virtuous human resources,
                generating advanced knowledge, and developing innovative technologies for
                the sustainable development of society.
            </p>
        </div>
    </section>

    <!-- Announcements -->
    @php
        use Carbon\Carbon;
        $oneHourAgo = Carbon::now()->subHour();
        $latestAnnouncements = \App\Models\Announcement::where('created_at', '>=', $oneHourAgo)
            ->latest()
            ->take(3)
            ->get();
    @endphp

    <section>
        <h3 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-2">
            <i class="fa-solid fa-bullhorn text-[#a83232]"></i> Latest Announcements
        </h3>

        @if($latestAnnouncements->isNotEmpty())
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($latestAnnouncements as $ann)
                    <div class="float-card bg-gradient-to-br from-[#fff] to-[#fdf7f8] border border-[#f1c5d3]/50 rounded-3xl p-6 shadow-md">
                        <h5 class="text-[#800000] font-bold mb-3 text-lg">{{ $ann->title }}</h5>
                        <p class="text-gray-700 mb-4 leading-relaxed">{{ \Illuminate\Support\Str::limit($ann->message, 100) }}</p>
                        <div class="text-sm text-gray-500 flex items-center justify-between">
                            <span><i class="fa-regular fa-clock"></i> {{ $ann->created_at->format('M d, Y') }}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-[#a83232]/10 text-[#a83232] font-semibold">New</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">No announcements yet.</p>
        @endif
    </section>

</main>
