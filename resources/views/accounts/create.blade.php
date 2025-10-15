<x-app-layout>
    <style>
        /* Container Styling */
        .container {
          max-width: 480px;
          background: #fff;
          padding: 2.5rem 2rem;
          border-radius: 1rem;
          box-shadow: 0 15px 40px rgba(128, 0, 0, 0.15); /* subtle maroon shadow */
          margin: auto;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Heading */
        h1.text-purple {
          color: #6a0dad;
          font-weight: 900;
          font-size: 2.5rem;
          letter-spacing: 1.2px;
          text-align: center;
          margin-bottom: 2.5rem;
          text-shadow: 1px 1px 4px rgba(128, 0, 0, 0.3);
        }

        /* Form labels */
        .form-label {
          font-weight: 600;
          color: #4b0a4b; /* dark maroon */
          display: block;
          margin-bottom: 0.5rem;
          letter-spacing: 0.03em;
        }

        /* Inputs and Select */
        .form-control,
        .form-select {
          width: 100%;
          padding: 0.65rem 1rem;
          font-size: 1rem;
          border: 2px solid #d9d9d9;
          border-radius: 0.75rem;
          transition: border-color 0.3s ease, box-shadow 0.3s ease;
          font-family: inherit;
          outline-offset: 2px;
          box-shadow: inset 2px 2px 6px #f0e6f5;
          background-color: #faf8fc;
        }

        .form-control:focus,
        .form-select:focus {
          border-color: #800000; /* maroon */
          box-shadow: 0 0 8px rgba(128, 0, 0, 0.4);
          background-color: #fff;
        }

        /* Button */
        .btn-success {
          display: block;
          width: 100%;
          padding: 0.75rem;
          font-size: 1.15rem;
          font-weight: 700;
          color: white;
          background: linear-gradient(135deg, #800000, #b22222);
          border: none;
          border-radius: 1rem;
          cursor: pointer;
          box-shadow: 0 8px 15px rgba(178, 34, 34, 0.4);
          transition: transform 0.3s ease, box-shadow 0.3s ease;
          user-select: none;
          letter-spacing: 0.05em;
        }

        .btn-success:hover {
          transform: translateY(-3px);
          box-shadow: 0 15px 25px rgba(178, 34, 34, 0.6);
          background: linear-gradient(135deg, #b22222, #800000);
        }

        /* Responsive */
        @media (max-width: 576px) {
          .container {
            padding: 2rem 1.5rem;
            max-width: 100%;
          }
          h1.text-purple {
            font-size: 2rem;
            margin-bottom: 2rem;
          }
        }
    </style>

    <div class="container py-4">
        <h1 class="mb-4 text-purple">Create Account</h1>

        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="" disabled selected>-- Select Role --</option>
                    <option value="Admin">Admin</option>
                    <option value="Instructor">Instructor</option>
                    <option value="Dean">Dean</option>
                    <option value="Programhead">Programhead</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</x-app-layout>
