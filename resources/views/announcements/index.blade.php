@extends('layouts.app')

@section('content')
<style>
    :root {
        --maroon: #800000;
        --maroon-dark: #660000;
        --maroon-light: #fff0f0;
        --text-dark: #333;
        --text-muted: #555;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    h2 {
        font-size: 2rem;
        color: var(--maroon);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .btn-new {
        background-color: var(--maroon);
        color: #fff;
        border-radius: 0.5rem;
        padding: 0.5rem 1.2rem;
        transition: background-color 0.3s;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .btn-new:hover {
        background-color: var(--maroon-dark);
        color: #fff;
        text-decoration: none;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        background-color: #fff;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .card h4 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: var(--maroon);
    }

    .card p {
        font-size: 1rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .card small {
        color: #888;
        display: block;
        margin-top: 10px;
    }

    .no-announcements {
        text-align: center;
        color: #888;
        font-style: italic;
        margin-top: 2rem;
    }
</style>

<div class="container">
    <h2>📢 Announcements</h2>

    <a href="{{ route('announcements.create') }}" class="btn-new">➕ New Announcement</a>

    @forelse ($announcements as $announcement)
        <div class="card mb-3">
            <div class="card-body">
                <h4>{{ $announcement->title }}</h4>
                <p>{{ $announcement->message }}</p>
                <small>📅 {{ $announcement->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</small>
            </div>
        </div>
    @empty
        <p class="no-announcements">No announcements</p>
    @endforelse
</div>
@endsection
