@extends('layouts.app')
@section('title','Preparar revisión')
@section('content')
<div class="page-header"><div><div class="text-primary fw-bold small text-uppercase mb-2">Paso 1 de 2 · Preparación</div><h1>Preparar una revisión</h1><p class="text-secondary mb-0">Selecciona dónde revisar y quién realizará el conteo. El sistema cargará automáticamente los artículos registrados en esa ubicación.</p></div><a class="btn btn-secondary" href="{{ route('revision.periodos') }}">Volver a periodos</a></div>
<div class="row g-3 mb-4"><div class="col-md-4"><div class="metric-card primary h-100"><div class="metric-body"><div class="metric-label">Periodo seleccionado</div><div class="h5 fw-bold">{{ $periodo->Nombre }}</div><div class="metric-note">Fecha de inicio: {{ \Illuminate\Support\Carbon::parse($periodo->Fecha_Inicio)->format('d/m/Y') }}</div></div></div></div><div class="col-md-8"><div class="card-panel p-4 h-100"><h5 class="fw-bold mb-2">¿Qué va a pasar al continuar?</h5><ol class="mb-0 ps-3 text-secondary"><li>Elegirás la ubicación y al responsable de revisar.</li><li>El sistema preparará una lista con los artículos que deberían estar ahí.</li><li>Podrás escanear el código de barras/QR o buscar un artículo para marcarlo como localizado.</li></ol></div></div></div>

<div class="card-panel p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="fw-bold mb-0">Progreso de este periodo</h5>
    <span class="badge {{ $resumen['pendientes']===0 ? 'bg-success' : 'bg-primary' }}">{{ $resumen['verificados'] }} de {{ $resumen['total'] }} artículos verificados</span>
  </div>
  @php $pct = $resumen['total'] ? round($resumen['verificados']/$resumen['total']*100) : 0; @endphp
  <div class="progress mb-3" style="height:10px"><div class="progress-bar {{ $pct===100?'bg-success':'' }}" style="width:{{ $pct }}%"></div></div>

  @if($resumen['pendientes']===0 && $resumen['abiertos']===0)
    <div class="alert alert-success py-2"><i class="bi bi-check-circle-fill me-2"></i>Todos los artículos de todas las ubicaciones están verificados. Ya se puede cerrar el periodo.</div>
  @else
    <div class="alert alert-warning py-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Faltan <strong>{{ $resumen['pendientes'] }}</strong> artículo(s) por verificar @if($resumen['abiertos']>0) y hay <strong>{{ $resumen['abiertos'] }}</strong> resguardo(s) abierto(s) @endif . El periodo no se podrá cerrar hasta completarlo.</div>
  @endif

  <div class="table-responsive">
    <table class="table table-sm align-middle mb-0">
      <thead><tr><th>Ubicación</th><th style="min-width:160px">Avance</th><th>Estado</th></tr></thead>
      <tbody>
      @foreach($ubicaciones as $u)
        <tr>
          <td>{{ $u->Nombre }} · Edificio {{ $u->Edificio }}</td>
          <td>{{ $u->verificados }} / {{ $u->total }}</td>
          <td>
            @if($u->pendientes===0)<span class="badge bg-success">Verificada</span>
            @elseif($u->verificados>0 || $u->abierto)<span class="badge bg-warning text-dark">En curso · faltan {{ $u->pendientes }}</span>
            @else<span class="badge bg-secondary">Sin revisar</span>@endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  @if($resguardos->isNotEmpty())
    <hr class="my-3">
    <div class="small text-secondary mb-2">Resguardos de este periodo:</div>
    <div class="table-responsive"><table class="table table-sm align-middle mb-0">
      <thead><tr><th>Ubicación</th><th>Responsable</th><th>Estado</th><th></th></tr></thead>
      <tbody>
      @foreach($resguardos as $r)
        <tr>
          <td>{{ $r->ubicacion->Nombre }}</td>
          <td>{{ $r->empleado->Nombre }} {{ $r->empleado->Apellido_Paterno }}</td>
          <td>@if($r->Cerrado)<span class="badge bg-success">Cerrado</span>@else<span class="badge bg-warning text-dark">Abierto</span>@endif</td>
          <td class="text-end">@if($r->Cerrado)<a class="btn btn-sm btn-secondary" href="{{ route('resguardo.print',$r) }}">Ver resguardo</a>@else<a class="btn btn-sm btn-primary" href="{{ route('revision.scan',$r) }}">Continuar escaneo</a>@endif</td>
        </tr>
      @endforeach
      </tbody>
    </table></div>
  @endif
</div>

<div class="card-form"><h4 class="fw-bold mb-1">Datos de la revisión</h4><p class="text-secondary mb-4">Los campos marcados con * son obligatorios.</p>
@if($ubicaciones->isEmpty())
  <div class="alert alert-info mb-0">No hay ubicaciones con artículos activos para revisar.</div>
@else
<form method="POST" action="{{ route('revision.start',$periodo) }}">@csrf
<div class="mb-4"><label class="form-label" for="ubicacion">1. ¿Qué lugar vas a revisar? *</label>
<select id="ubicacion" class="form-select" name="FkId_Ubicacion" required>
  <option value="">Selecciona edificio o ubicación</option>
  @foreach($ubicaciones as $u)
    <option value="{{ $u->Id_Ubicacion }}" @selected(old('FkId_Ubicacion')==$u->Id_Ubicacion) @disabled($u->pendientes===0)>{{ $u->Nombre }} · Edificio {{ $u->Edificio }} · Planta {{ $u->Planta }} — {{ $u->pendientes===0 ? 'verificada' : $u->verificados.'/'.$u->total.' verificados' }}</option>
  @endforeach
</select>
<div class="form-text">Solo aparecen ubicaciones que tienen artículos registrados. Las ya verificadas completas se muestran deshabilitadas.</div></div>
<div class="mb-4"><label class="form-label" for="empleado">2. ¿Quién es el empleado responsable de estos artículos? *</label>
<select id="empleado" class="form-select" name="FkId_Empleado" required disabled><option value="">Primero elige una ubicación</option></select>
<div class="form-text">Solo aparecen las personas que tienen resguardo de artículos en la ubicación elegida.</div></div>
<div class="alert alert-info mt-0"><i class="bi bi-info-circle-fill me-2"></i>Al continuar se crea una revisión abierta. Podrás guardar el avance y cerrar el resguardo cuando termines.</div><div class="d-flex flex-wrap gap-2"><button class="btn btn-primary btn-lg"><i class="bi bi-clipboard-check me-2"></i>Cargar lista de artículos</button><a class="btn btn-secondary btn-lg" href="{{ route('revision.periodos') }}">Cancelar</a></div></form>
<script>
(function(){
  const data=@json($empleadosPorUbicacion);
  const oldEmp='{{ old('FkId_Empleado') }}';
  const ub=document.getElementById('ubicacion'), emp=document.getElementById('empleado');
  function fill(){
    const lista=data[ub.value]||[];
    emp.replaceChildren();
    const first=document.createElement('option'); first.value='';
    first.textContent=ub.value?'Selecciona a la persona responsable':'Primero elige una ubicación';
    emp.appendChild(first);
    lista.forEach(e=>{
      const o=document.createElement('option'); o.value=e.id;
      o.textContent=e.nombre+' · No. trabajador '+e.trabajador+' — '+(e.pendientes===0?'verificado':e.verificados+'/'+e.total+' verificados');
      if(e.pendientes===0) o.disabled=true;
      if(String(e.id)===oldEmp) o.selected=true;
      emp.appendChild(o);
    });
    emp.disabled=!ub.value;
  }
  ub.addEventListener('change',fill); fill();
})();
</script>
@endif
</div>
@endsection