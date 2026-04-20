<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body {
        background: linear-gradient(135deg, #43cea2, #185a9d);
        height: 100vh;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-success {
        border-radius: 10px;
    }
</style>

<body class="d-flex align-items-center justify-content-center">

    <div class="card p-4" style="width:350px;">
        <h3 class="text-center mb-3">📝 Register</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            <input type="text" name="name" class="form-control mb-3" placeholder="Name">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email">
            <input type="password" name="password" class="form-control mb-3" placeholder="Password">

            <button class="btn btn-success w-100">Register</button>
        </form>

        <a href="/login" class="text-center mt-3 d-block">Already have account?</a>
    </div>

</body>

</html>