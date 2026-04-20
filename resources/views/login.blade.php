<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body {
        background: linear-gradient(135deg, #667eea, #764ba2);
        height: 100vh;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        border-radius: 10px;
    }
</style>

<body class="d-flex align-items-center justify-content-center">

    <div class="card p-4" style="width:350px;">
        <h3 class="text-center mb-3">🔐 Login</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <input type="email" name="email" class="form-control mb-3" placeholder="Email">
            <input type="password" name="password" class="form-control mb-3" placeholder="Password">

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <a href="/register" class="text-center mt-3 d-block">Create Account</a>
    </div>

</body>

</html>