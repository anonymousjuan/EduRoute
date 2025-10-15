@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
    }
    label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
    }
    input[type="file"], input[type="text"], select {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
    }
    button {
        background-color: #800000;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<div class="container">
    <h2>Import Curriculum (DOCX)</h2>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('curriculum.import.docx') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Year of Implementation --}}
        <label for="year_of_implementation">Year of Implementation:</label>
        <input type="text" name="year_of_implementation" id="year_of_implementation" placeholder="e.g., 2024-2025" required>

        {{-- DOCX File Upload --}}
        <label for="file">Select DOCX File:</label>
        <input type="file" name="file" id="file" accept=".doc,.docx" required>

        <button type="submit">📥 Import Curriculum</button>
    </form>
</div>
@endsection
