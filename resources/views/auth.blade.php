@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login · Expense Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/custom.css'])

</head>

<body>
    <div class="auth-wrap">
        <div class="panel auth-card">
            <div class="text-center mb-4"><span class="brand justify-content-center"><i
                        class="bi bi-wallet2"></i>Expense Tracker</span>
                <p class="text-secondary small mt-2 mb-0">Track money, stay in control.</p>
            </div>
            <ul class="nav nav-pills nav-fill auth-tabs gap-2 mb-4">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
                        data-bs-target="#login">Login</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                        data-bs-target="#register">Register</button></li>
            </ul>
            <div class="tab-content">
                <form class="tab-pane fade show active" id="login" action="{{ route('login.store') }}"
                    method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email"
                            class="form-control" placeholder="you@example.com" name="email"
                            value="{{ old('email') }}"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Password</label><input type="password"
                            class="form-control" name="password" value="{{ old('password') }}"
                            placeholder="Enter password" required></div>
                    <button class="btn btn-ink w-100 py-2">Login</button>
                </form>


                <form class="tab-pane fade" id="register" action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label fw-semibold">Full name</label>
                        <input name="full_name" value="{{ old('full_name') }}" class="form-control"
                            placeholder="Your name" required>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="you@example.com" required>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create password"
                            required>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repeat password" required>
                    </div>
                    <button class="btn btn-ink w-100 py-2">Create account</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
