<x-app-layout>
    {{-- Google Fonts for smooth modern typography --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* Use Inter font for modern UI */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Header */
        h1.text-purple {
            color: #800000; /* Deep purple */
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Custom Header Background */
        .custom-bg {
            background: #800000;
        }

        .custom-bg-light {
            background: #f4f6fc;
        }

        /* Card Enhancements */
        .card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(38, 50, 56, 0.1);
        }

        .card-header {
            font-weight: 600;
            font-size: 1.25rem;
            letter-spacing: 0.4px;
            color: black;
        }

        /* Table Styling */
        .table th,
        .table td {
            vertical-align: middle;
            font-size: 0.95rem;
            padding: 1rem 1.25rem;
            transition: background-color 0.3s ease;
        }

        .table thead {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f4ff;
            transition: background 0.3s ease;
        }

        /* Center only the Actions column */
        .table th.text-center,
        .table td.text-center {
            text-align: center;
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-sm i {
            font-size: 0.9rem;
        }

        .btn-success {
            border: none;
            color: black;
        }

        .btn-success:hover {
            filter: brightness(1.1);
            transform: scale(1.03);
            color: #800000;
        }

        .btn-info {
            background: #00bcd4;
            border: none;
            color: #800000;
        }

        .btn-info:hover {
            opacity: 0.9;
            transform: scale(1.05);
            color: #800000;
        }

        .btn-warning {
            background: #ff9800;
            border: none;
            color: white;
        }

        .btn-warning:hover {
            opacity: 0.9;
            transform: scale(1.05);
            color: white;
        }

        .btn-danger {
            background: #f44336;
            border: none;
            color: white;
        }

        .btn-danger:hover {
            opacity: 0.9;
            transform: scale(1.05);
            color: white;
        }

        /* Alert Styling */
        .alert {
            border-radius: 0.6rem;
            font-size: 0.95rem;
            box-shadow: 0 6px 12px rgba(38, 50, 56, 0.1);
        }

        /* Responsive Buttons Row */
        .d-flex.justify-content-end.mb-4 {
            gap: 0.75rem;
        }

        /* Mobile Optimization */
        @media (max-width: 576px) {
            .table-responsive {
                border-radius: 0.75rem;
                overflow: hidden;
            }

            .table th,
            .table td {
                font-size: 0.875rem;
                padding: 0.75rem;
            }

            .btn {
                font-size: 0.85rem;
                padding: 0.4rem 0.65rem;
            }
        }
    </style>

    <div class="container py-5">
        <h1 class="mb-4 text-center text-purple fw-bold">Instructors</h1>

        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('instructors.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Instructor
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead>
                            <tr class="custom-bg-light text-dark">
                                <th class="py-3">Name</th>
                                <th class="py-3">Email</th>
                                <th class="py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($instructors as $instructor)
                                <tr class="table-row align-middle">
                                    <td class="fw-semibold">{{ $instructor->name }}</td>
                                    <td>{{ $instructor->email }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('instructors.show', $instructor->id) }}" class="btn btn-info btn-sm me-1 shadow-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('instructors.edit', $instructor->id) }}" class="btn btn-warning btn-sm me-1 shadow-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('instructors.destroy', $instructor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Delete this instructor?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No instructors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</x-app-layout>
