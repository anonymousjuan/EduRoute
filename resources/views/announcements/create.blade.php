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

    body {
        background-color: #f8f8f8;
    }

    .container {
        max-width: 700px;
        margin: 3rem auto;
        padding: 2.5rem 2rem;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    h2 {
        font-size: 2rem;
        color: var(--maroon);
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 700;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        display: block;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%; /* make inputs full width */
    }

    .form-control:focus {
        border-color: var(--maroon);
        box-shadow: 0 0 5px rgba(128,0,0,0.3);
        outline: none;
    }

    .form-control::placeholder {
        color: #888;
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Bigger message box */
    textarea.form-control {
        min-height: 200px;
        font-size: 1.05rem;
        width: 100%; /* match full width */
    }

    .btn-primary {
        background-color: var(--maroon);
        border-color: var(--maroon);
        color: #fff;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        width: 100%; /* make button same width as inputs */
        font-size: 1.1rem; /* slightly bigger text for emphasis */
        transition: background-color 0.3s;
        margin-top: 0.5rem;
    }

    .btn-primary:hover {
        background-color: var(--maroon-dark);
        border-color: var(--maroon-dark);
    }

    .mb-3 {
        margin-bottom: 1.5rem;
    }

    .current-time {
        background-color: #f5f5f5;
        color: var(--text-muted);
    }

    small.hint {
        font-size: 0.8rem;
        color: #888;
        display: block;
        margin-top: 5px;
    }

    @media(max-width: 576px){
        .container {
            padding: 2rem 1rem;
            margin: 2rem 1rem;
        }
    }
</style>

<div class="container">
    <h2>📢 Create Announcement</h2>

    <form method="POST" action="{{ route('announcements.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter announcement title" required>
            <small class="hint">Keep it short and clear</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" placeholder="Write your announcement here..." required></textarea>
            <small class="hint">Provide all necessary details</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Time</label>
            <input type="text" class="form-control current-time" value="{{ $currentTime }}" readonly>
            <small class="hint">Timestamp when announcement is created</small>
        </div>

        <button type="submit" class="btn-primary">Send Announcement</button>
    </form>
</div>
@endsection
