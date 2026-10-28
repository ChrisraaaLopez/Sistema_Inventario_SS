@extends('layouts.app')
@section('title','Preparar revisión')
@section('content')
<div class="page-header"><div><div class="text-primary fw-bold small text-uppercase mb-2">Paso 1 de 2 · Preparación</div><h1>Preparar una revisión</h1><p class="text-secondary mb-0">Selecciona dónde revisar y quién realizará el conteo. El sistema cargará automáticamente los artículos registrados en esa ubicación.</p></div><a class="btn btn-secondary" href="{{ route('revision.periodos') }}">Volver a periodos</a></div>
<div class="row g-3 mb-4"><div class="col-md-4"><div class="metric-card primary h-100"><div class="metric-body"><div class="metric-label">Periodo seleccionado</div><div class="h5 fw-bold">{{ $periodo->Nombre }}</div><div class="metric-note">Fecha de inicio: {{ \Illuminate\Support\Carbon::parse($periodo->Fecha_Inicio)->format('d/m/Y') }}</div></div></div></div><div class="col-md-8"><div class="card-panel p-4 h-100"><h5 class="fw-bold mb-2">¿Qué va a pasar al continuar?</h5><ol class="mb-0 ps-3 text-secondary"><li>Elegirás la ubicación y al responsable de revisar.</li><li>El sistema preparará una lista con los artículos que deberían estar ahí.</li><li>Podrás escanear el código de barras/QR o buscar un artículo para marcarlo como localizado.</li></ol></div></div></div>

<div class="card-panel p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="fw-bold mb-0">Progreso de este periodo</h5>
    <span class="badge {{ $ubicacionesPendientes->isEmpty() ? 'bg-success' : 'bg-primary' }}">
      {{ $ubicaciones->count() - $ubicacionesPendientes->count() }} de {{ $ubicaciones->count() }} ubicaciones cubiertas
    </span>
  </div>

  @if($resguardos->isEmpty())
    <p class="text-secondary mb-0">Todavía no se ha iniciado ninguna revisión dentro de este periodo.</p>
  @else
    <div class="table-responsive mb-0">
      <table class="table table-sm align-middle mb-0">
        <thead>
          <tr><th>Ubicación</th><th>Responsable</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
          @foreach($resguardos as $r)
            <tr>
              <td>{{ $r->ubicacion->Nombre }}</td>
              <td>{{ $r->empleado->Nombre }} {{ $r->empleado->Apellido_Paterno }}</td>
              <td>
                @if($r->Cerrado)
                  <span class="badge bg-success">Cerrado</span>
                @else
                  <span class="badge bg-warning text-dark">Abierto</span>
                @endif
              </td>
              <td class="text-end">
                @if($r->Cerrado)
                  <a class="btn btn-sm btn-secondary" href="{{ route('resguardo.print',$r) }}">Ver resguardo</a>
                @else
                  <a class="btn btn-sm btn-primary" href="{{ route('revision.scan',$r) }}">Continuar escaneo</a>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  @if($ubicacionesPendientes->isNotEmpty())
    <hr class="my-3">
    <div class="small text-secondary mb-1">Ubicaciones que aún faltan por revisar en este periodo:</div>
    <div class="d-flex flex-wrap gap-2">
      @foreach($ubicacionesPendientes as $u)
        <span class="badge bg-light text-dark border">{{ $u->Nombre }} · Edificio {{ $u->Edificio }}</span>
      @endforeach
    </div>
  @else
    <hr class="my-3">
    <div class="alert alert-success mb-0 py-2"><i class="bi bi-check-circle-fill me-2"></i>Todas las ubicaciones de este periodo ya tienen una revisión iniciada.</div>
  @endif
</div>

<div class="card-form"><h4 class="fw-bold mb-1">Datos de la revisión</h4><p class="text-secondary mb-4">Los campos marcados with * son obligatorios.</p><form method="POST" action="{{ route('revision.start',$periodo) }}">@csrf
<div class="mb-4"><label class="form-label" for="ubicacion">1. ¿Qué lugar vas a revisar? *</label><select id="ubicacion" class="form-select" name="FkId_Ubicacion" required><option value="">Selecciona edificio o ubicación</option>@foreach($ubicaciones as $u)<option value="{{ $u->Id_Ubicacion }}" @selected(old('FkId_Ubicacion')==$u->Id_Ubicacion)>{{ $u->Nombre }} · Edificio {{ $u->Edificio }} · Planta {{ $u->Planta }} @if(!$ubicacionesPendientes->contains('Id_Ubicacion', $u->Id_Ubicacion)) (ya tiene revisión) @endif</option>@endforeach</select><div class="form-text">Ejemplo: laboratorio, oficina, almacén o aula. Puedes elegir una ya cubierta si necesitas revisar otro empleado ahí.</div></div>
<div class="mb-4"><label class="form-label" for="empleado">2. ¿Quién es el empleado responsable de estos artículos? *</label><select id="empleado" class="form-select" name="FkId_Empleado" required><option value="">Selecciona a la persona responsable</option>@foreach($empleados as $e)<option value="{{ $e->Id_Empleado }}" @selected(old('FkId_Empleado')==$e->Id_Empleado)>{{ $e->Nombre }} {{ $e->Apellido_Paterno }} · No. trabajador {{ $e->N_Trabajador }}</option>@endforeach</select><div class="form-text">Los artículos asignados a esta persona en la ubicación elegida se cargarán como los que debe tener a su cargo.</div></div>
<div class="alert alert-info mt-0"><i class="bi bi-info-circle-fill me-2"></i>Al continuar se crea una revisión abierta. Podrás guardar el avance y cerrar el resguardo cuando termines.</div><div class="d-flex flex-wrap gap-2"><button class="btn btn-primary btn-lg"><i class="bi bi-clipboard-check me-2"></i>Cargar lista de artículos</button><a class="btn btn-secondary btn-lg" href="{{ route('revision.periodos') }}">Cancelar</a></div></form></div>
@endsection