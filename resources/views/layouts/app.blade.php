<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Inventario') | Inventario TSJ</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/revision.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #0f1f3d;
            --navy-2: #142a50;
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --border: #e5eaf2;
            --text: #172033;
            --muted: #64748b;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --shadow: 0 10px 30px rgba(15, 31, 61, .07);
        }

        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            font-family: Inter, "Segoe UI", system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .wrapper { min-height: 100vh; display: flex; }
        .sidebar {
            position: sticky;
            top: 0;
            width: 255px;
            height: 100vh;
            flex: 0 0 255px;
            padding: 16px 12px;
            overflow-y: auto;
            background: linear-gradient(180deg, var(--navy) 0%, #10284e 100%);
            box-shadow: 8px 0 30px rgba(15,31,61,.08);
            z-index: 20;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            padding: 12px 10px 20px;
            margin-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .brand:hover { color: #fff; }
        .brand-mark {
            width: 44px; height: 44px; border-radius: 13px;
            display: grid; place-items: center;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff; box-shadow: 0 8px 20px rgba(37,99,235,.3);
            font-size: 1.3rem;
        }
        .brand-copy { display:flex; flex-direction:column; line-height:1.15; }
        .brand-copy strong { font-size: 1.02rem; letter-spacing:.01em; }
        .brand-copy small { margin-top:5px; color:rgba(255,255,255,.58); font-size:.68rem; }

        .nav-section {
            color: rgba(255,255,255,.42);
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .12em;
            padding: 16px 12px 7px;
        }
        .sidebar .nav { gap: 3px; }
        .sidebar .nav-link {
            display:flex; align-items:center; gap:11px;
            color: rgba(255,255,255,.72);
            padding: 11px 13px;
            margin: 1px 0;
            border-radius: 11px;
            font-size: .91rem;
            font-weight: 600;
            transition: .2s ease;
        }
        .sidebar .nav-link i { width: 20px; text-align:center; font-size:1rem; }
        .sidebar .nav-link:hover { color:#fff; background:rgba(255,255,255,.08); transform:translateX(2px); }
        .sidebar .nav-link.active {
            color:#fff;
            background: linear-gradient(90deg, rgba(37,99,235,.95), rgba(59,130,246,.78));
            box-shadow: 0 8px 18px rgba(4,15,35,.18);
        }

        .main-content { min-width: 0; flex: 1; padding: 0 34px 42px; }
        .topbar {
            height: 76px;
            display:flex; align-items:center; justify-content:space-between;
            margin: 0 -34px 30px;
            padding: 0 34px;
            background: rgba(255,255,255,.88);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }
        .topbar-title { font-weight:800; font-size:1.05rem; color:#172033; }
        .topbar-subtitle { color:var(--muted); font-size:.8rem; margin-top:2px; }
        .topbar-badge {
            display:flex; align-items:center; gap:8px;
            padding:8px 12px; border-radius:999px;
            background:#eff6ff; color:#1d4ed8; font-size:.78rem; font-weight:700;
        }
        .topbar-badge .dot { width:7px; height:7px; border-radius:50%; background:#22c55e; }

        .page-shell { max-width: 1500px; margin:0 auto; }
        .page-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:22px; }
        .page-header h1, .page-header h2, .main-content > h1 {
            margin:0 0 18px; font-size:1.85rem; font-weight:800; letter-spacing:-.03em; color:#111827;
        }
        .page-header h1 { margin-bottom:0; }
        .date-pill { color:var(--muted); font-size:.88rem; }

        .card-panel, .card-form, .mini-panel, .metric-card {
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:16px;
            box-shadow:var(--shadow);
        }
        .card-form { padding:24px; max-width: 900px; }
        .card-form form { margin:0 !important; }

        .panel-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:18px; margin-bottom:22px; }
        .metric-card { position:relative; min-height:145px; padding:22px; overflow:hidden; }
        .metric-card::after { content:""; position:absolute; right:-30px; top:-30px; width:100px; height:100px; border-radius:50%; background:rgba(37,99,235,.06); }
        .metric-body { position:relative; z-index:1; }
        .metric-label { color:var(--muted); font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.09em; margin-bottom:13px; }
        .metric-value { font-size:2.25rem; font-weight:850; line-height:1; color:#0f172a; }
        .metric-note { color:#94a3b8; font-size:.8rem; margin-top:10px; }
        .metric-card.success .metric-value { color:var(--success); }
        .metric-card.danger .metric-value { color:var(--danger); }
        .metric-card.primary .metric-value { color:var(--primary); }

        .dashboard-row { display:grid; gap:18px; margin-bottom:20px; }
        .mini-panel { min-height:180px; padding:22px; }
        .mini-panel h3 { margin:0 0 15px; font-size:1rem; font-weight:800; }
        .muted-empty { color:var(--muted); margin:0; font-size:.9rem; }

        .action-row { display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; }
        .btn { border-radius:10px; font-weight:700; padding:.55rem .9rem; transition:.18s ease; }
        .btn:hover { transform:translateY(-1px); }
        .btn-primary { background:linear-gradient(135deg,var(--primary),#3b82f6); border:0; box-shadow:0 7px 15px rgba(37,99,235,.18); }
        .btn-primary:hover { background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .btn-secondary { background:#eef4ff; color:#2452a2; border:1px solid #dbe7ff; }
        .btn-warning { color:#7c4a03; background:#fff7e6; border-color:#ffe1a8; }
        .btn-danger { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
        .btn-info { background:#eff6ff; color:#1d4ed8; border-color:#dbeafe; }
        .btn-success { background:#ecfdf5; color:#15803d; border-color:#bbf7d0; }

        .table-wrap { overflow-x:auto; border:1px solid var(--border); border-radius:16px; background:#fff; box-shadow:var(--shadow); }
        .table { margin:0; --bs-table-bg:transparent; }
        .table > :not(caption) > * > * { padding:.9rem 1rem; border-bottom-color:var(--border); }
        .table > thead > tr > th, .table > tr:first-child > th { background:#f8fafc; color:#475569; font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; font-weight:800; white-space:nowrap; }
        .table tbody tr:hover, .table > tr:hover { background:#f8fbff; }
        .table img { border-radius:10px; object-fit:cover; max-height:60px; }
        .table form { display:inline; }
        .table td:last-child { white-space:nowrap; }

        .form-control, .form-select {
            min-height:44px; border:1px solid #dbe2ec; border-radius:10px; background:#fff; color:var(--text); box-shadow:none;
        }
        .form-control:focus, .form-select:focus { border-color:#60a5fa; box-shadow:0 0 0 .2rem rgba(37,99,235,.1); }
        .form-label { color:#334155; font-size:.83rem; font-weight:750; margin-bottom:.45rem; }
        .main-content > form:not(.d-none) { background:#fff; border:1px solid var(--border); border-radius:16px; padding:18px; box-shadow:var(--shadow); }

        .badge { border-radius:999px; padding:.45em .75em; font-weight:750; }
        .alert { border:0; border-radius:13px; box-shadow:var(--shadow); margin-top:22px; }
        .pagination { margin-top:18px; }
        .page-link { border-color:var(--border); color:#2563eb; }
        .page-item.active .page-link { background:#2563eb; border-color:#2563eb; }

        @media (max-width: 1100px) {
            .panel-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .sidebar { width:220px; flex-basis:220px; }
            .main-content { padding-left:24px; padding-right:24px; }
            .topbar { margin-left:-24px; margin-right:-24px; padding-left:24px; padding-right:24px; }
        }
        @media (max-width: 760px) {
            .wrapper { display:block; }
            .sidebar { position:relative; width:100%; height:auto; max-height:none; }
            .sidebar .nav { display:grid; grid-template-columns:repeat(2,1fr); }
            .nav-section { grid-column:1/-1; }
            .main-content { padding:0 15px 30px; }
            .topbar { margin:0 -15px 22px; padding:0 15px; height:68px; }
            .panel-grid { grid-template-columns:1fr; }
            .page-header { align-items:flex-start; flex-direction:column; }
            .topbar-badge { display:none; }
            .table-wrap { border-radius:12px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <nav class="sidebar">
        <a href="{{ url('/') }}" class="brand" aria-label="Inventario TSJ">
            <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
            <span class="brand-copy"><strong>Inventario TSJ</strong><small>Control Patrimonial</small></span>
        </a>

        @php($rolActual = auth()->user()?->rol?->Nombre)
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a></li>
            @if($rolActual === 'Administrador' || $rolActual === 'Consulta')
                <li class="nav-section">Inventario</li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('articulos.*') ? 'active' : '' }}" href="{{ route('articulos.index') }}"><i class="bi bi-boxes"></i>Artículos</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}" href="{{ route('empleados.index') }}"><i class="bi bi-people-fill"></i>Empleados</a></li>
                @if($rolActual === 'Administrador')
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}" href="{{ route('vehiculos.index') }}"><i class="bi bi-bus-front-fill"></i>Vehículos escolares</a></li>
                @endif
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('facturas.*') ? 'active' : '' }}" href="{{ route('facturas.index') }}"><i class="bi bi-receipt"></i>Facturas</a></li>
                <li class="nav-section">Catálogos</li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('marcas.*') ? 'active' : '' }}" href="{{ route('marcas.index') }}"><i class="bi bi-tags-fill"></i>Marcas</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('tipos.*') ? 'active' : '' }}" href="{{ route('tipos.index') }}"><i class="bi bi-diagram-3-fill"></i>Tipos</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('ubicaciones.*') ? 'active' : '' }}" href="{{ route('ubicaciones.index') }}"><i class="bi bi-geo-alt-fill"></i>Ubicaciones</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('puestos.*') ? 'active' : '' }}" href="{{ route('puestos.index') }}"><i class="bi bi-person-badge-fill"></i>Puestos</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('modelos.*') ? 'active' : '' }}" href="{{ route('modelos.index') }}"><i class="bi bi-cpu-fill"></i>Modelos</a></li>
                @if($rolActual === 'Administrador')
                    <li class="nav-section">Administración</li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}"><i class="bi bi-person-gear"></i>Cuentas de acceso</a></li>
                @endif
            @endif
            @if(in_array($rolActual, ['Administrador', 'Revisor', 'Consulta'], true))
                <li class="nav-section">Revisión patrimonial</li>
                @if(in_array($rolActual, ['Administrador', 'Revisor'], true))
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('revision.periodos*') || request()->routeIs('revision.scan') || request()->routeIs('revision.select') ? 'active' : '' }}" href="{{ route('revision.periodos') }}"><i class="bi bi-qr-code-scan"></i>Revisiones</a></li>
                @endif
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('revision.history') ? 'active' : '' }}" href="{{ route('revision.history') }}"><i class="bi bi-clock-history"></i>Histórico</a></li>
            @endif
        </ul>
    </nav>

    <main class="main-content">
        <header class="topbar">
            <div><div class="topbar-title">Sistema de Inventario</div><div class="topbar-subtitle">Administración y control patrimonial</div></div>
            @auth
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block"><div class="fw-bold">{{ Auth::user()->Nombre_Usuario }}</div><small class="text-secondary">{{ Auth::user()->rol?->Nombre ?? 'Sin rol asignado' }}</small></div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf<button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión</button></form>
                </div>
            @endauth
        </header>

        @if(session('ok'))
            <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>{{ session('ok') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
