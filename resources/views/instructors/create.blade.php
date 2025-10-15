@extends('layouts.app')

@section('content')
{{-- Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
    /* Use Inter font for a modern, clean look */
    body, .container {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
    }

    .container {
        max-width: 480px;
        margin: 3rem auto;
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(38, 50, 56, 0.1);
    }

    h1 {
        font-weight: 700;
        font-size: 2rem;
        color: #800000;
        margin-bottom: 1.8rem;
        text-align: center;
        letter-spacing: 0.5px;
    }

    label {
        font-weight: 600;
        color: #374151; /* dark slate */
        display: block;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        user-select: none;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select.form-control {
        width: 100%;
        padding: 0.65rem 1rem;
        font-size: 1rem;
        border: 2px solid #e2e8f0; /* light gray */
        border-radius: 0.75rem;
        outline-offset: 2px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        font-family: inherit;
        color: #111827; /* almost black */
        box-sizing: border-box;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select.form-control:focus {
        border-color: #800000;
        box-shadow: 0 0 8px rgba(75, 43, 225, 0.4);
        outline: none;
    }

    .mb-3 {
        margin-bottom: 1.5rem;
    }

    .btn-success {
        background: #800000;
        border: none;
        padding: 0.65rem 1.8rem;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 0.75rem;
        color: white;
        cursor: pointer;
        box-shadow: 0 6px 12px rgba(75, 43, 225, 0.3);
        transition: all 0.25s ease-in-out;
        user-select: none;
        margin-right: 1rem;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }

    .btn-success:hover,
    .btn-success:focus {
        background: #800000;
        box-shadow: 0 8px 20px rgba(75, 43, 225, 0.5);
        transform: translateY(-2px);
        outline: none;
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #374151;
        border: none;
        padding: 0.65rem 1.8rem;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 0.75rem;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        transition: background-color 0.3s ease, color 0.3s ease;
        user-select: none;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }

    .btn-secondary:hover,
    .btn-secondary:focus {
        background-color: #cbd5e1;
        color: #1f2937;
        outline: none;
    }

    /* Responsive tweaks */
    @media (max-width: 576px) {
        .container {
            margin: 2rem 1rem;
            padding: 2rem 1.5rem;
        }

        .btn-success,
        .btn-secondary {
            width: 100%;
            margin-right: 0;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
    }
</style>

<div class="container">
    <h1>Add Instructor</h1>

    <form action="{{ route('instructors.store') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" class="form-control" required autocomplete="name" autofocus>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" class="form-control" required autocomplete="email">
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>

        <div class="mb-3">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
                <option value="" disabled selected>Select role</option>
                <option value="instructor">Instructor</option>
                <option value="admin">Admin</option>
                <option value="programhead">Program Head</option>
                <!-- Add more roles as needed -->
            </select>
        </div>

        <button class="btn btn-success" type="submit">Save</button>
        <a href="{{ route('instructors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
