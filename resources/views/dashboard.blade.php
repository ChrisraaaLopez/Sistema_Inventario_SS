@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php($rolActual = auth()->user()?->rol?->Nombre)
<div class="page-shell">
    <div class="page-header"><div><h1>Dashboard</h1><div class="date-pill">Resumen general del sistema</div></div><span class="badge text-bg-light">{{ now()->format('d/m/Y') }}</span></div>
    <div class="panel-grid">
        <a class="metric-card primary text-decoration-none" href="{{ $rolActual === 'Revisor' ? route('revision.periodos') : route('articulos.index') }}"><div class="metric-body"><div class="metric-label">Total de artículos</div><div class="metric-value">{{ $totales['articulos'] }}</div><div class="metric-note">Consultar inventario →</div></div></a>
        <a class="metric-card success text-decoration-none" href="{{ $rolActual === 'Revisor' ? route('revision.periodos') : route('articulos.index') }}"><div class="metric-body"><div class="metric-label">En buen estado</div><div class="metric-value">{{ $totales['bien'] }}</div><div class="metric-note">Artículos activos en buen estado</div></div></a>
        <a class="metric-card danger text-decoration-none" href="{{ $rolActual === 'Revisor' ? route('revision.periodos') : route('articulos.index') }}"><div class="metric-body"><div class="metric-label">En proceso de baja / baja</div><div class="metric-value">{{ $totales['bajas'] }}</div><div class="metric-note">Requieren seguimiento</div></div></a>
        <a class="metric-card primary text-decoration-none" href="{{ $rolActual === 'Revisor' ? route('revision.periodos') : route('empleados.index') }}"><div class="metric-body"><div class="metric-label">Empleados activos</div><div class="metric-value">{{ $totales['empleados'] }}</div><div class="metric-note">Consultar empleados →</div></div></a>
    </div>
    <div class="dashboard-row" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr))">
        <section class="mini-panel"><h3><i class="bi bi-activity me-2 text-primary"></i>Artículos por estado</h3>
            @forelse($articulosPorEstado as $estado)<div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span>{{ $estado->Estado }}</span><strong>{{ $estado->total }}</strong></div>@empty<p class="muted-empty">Todavía no hay artículos registrados.</p>@endforelse
        </section>
        <section class="mini-panel"><h3><i class="bi bi-qr-code-scan me-2 text-primary"></i>Periodo de revisión</h3>
            @if($periodoActivo)<p class="mb-1 fw-bold">{{ $periodoActivo->Nombre }}</p><p class="muted-empty">Inicio: {{ optional($periodoActivo->Fecha_Inicio)->format('d/m/Y') ?? $periodoActivo->Fecha_Inicio }}</p><p class="muted-empty">Resguardos abiertos: {{ $totales['resguardos_abiertos'] }}</p>
                @if(in_array($rolActual,['Administrador','Revisor'],true))<div class="action-row"><a class="btn btn-primary" href="{{ route('revision.periodos') }}">Abrir revisión</a></div>@endif
            @else<p class="muted-empty">No hay un periodo de revisión abierto.</p>@if($rolActual==='Administrador')<div class="action-row"><a class="btn btn-primary" href="{{ route('revision.periodos.create') }}">Crear periodo</a></div>@endif @endif
        </section>
    </div>
    <section class="mini-panel mb-4"><h3><i class="bi bi-clock-history me-2 text-primary"></i>Periodos recientes</h3>
        @forelse($periodosRecientes as $periodo)<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 py-2 border-bottom"><div><strong>{{ $periodo->Nombre }}</strong><div class="metric-note">{{ $periodo->Fecha_Inicio }} · {{ $periodo->resguardos_count }} resguardos</div></div><span class="badge {{ $periodo->Estado==='Abierto'?'text-bg-success':'text-bg-secondary' }}">{{ $periodo->Estado }}</span></div>@empty<p class="muted-empty">Aún no hay periodos creados.</p>@endforelse
        @if(in_array($rolActual,['Administrador','Revisor'],true))<div class="action-row"><a href="{{ route('revision.periodos') }}" class="btn btn-secondary">Ver revisiones</a><a href="{{ route('revision.history') }}" class="btn btn-secondary">Ver histórico</a></div>@endif
    </section>
    @if($rolActual==='Administrador')<div class="action-row"><a href="{{ route('articulos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo artículo</a><a href="{{ route('empleados.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i>Nuevo empleado</a><a href="{{ route('articulos.index') }}" class="btn btn-secondary"><i class="bi bi-search me-1"></i>Consultar inventario</a></div>@elseif($rolActual==='Consulta')<div class="action-row"><a href="{{ route('articulos.index') }}" class="btn btn-primary">Consultar inventario</a><a href="{{ route('revision.history') }}" class="btn btn-secondary">Consultar histórico</a></div>@elseif($rolActual==='Revisor')<div class="action-row"><a href="{{ route('revision.periodos') }}" class="btn btn-primary">Ir a revisiones</a></div>@endif
</div>
@endsection
