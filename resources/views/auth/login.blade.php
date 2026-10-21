<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Iniciar sesión | Inventario TSJ</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body{min-height:100vh;display:grid;place-items:center;background:linear-gradient(135deg,#eaf1ff,#f8fafc);font-family:Segoe UI,Arial,sans-serif}.login-card{width:min(92vw,440px);padding:36px;background:#fff;border:1px solid #e5eaf2;border-radius:20px;box-shadow:0 20px 55px #0f1f3d18}.logo{width:56px;height:56px;display:grid;place-items:center;margin:0 auto 18px;border-radius:16px;background:#2563eb;color:#fff;font-size:25px}.form-control{min-height:46px}.btn-primary{min-height:46px;font-weight:700}
    </style>
</head>
<body>
    <main class="login-card">
        <div class="logo">▣</div>
        <h1 class="h3 text-center fw-bold">Inventario TSJ</h1>
        <p class="text-secondary text-center mb-4">Control patrimonial y revisión de activos</p>
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('login.authenticate') }}">
            @csrf
            <div class="mb-3">
                <label for="Nombre_Usuario" class="form-label">Usuario</label>
                <input id="Nombre_Usuario" name="Nombre_Usuario" class="form-control" value="{{ old('Nombre_Usuario') }}" autocomplete="username" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" name="password" type="password" class="form-control" autocomplete="current-password" required>
            </div>
            <button class="btn btn-primary w-100">Iniciar sesión</button>
        </form>
    </main>
</body>
</html>