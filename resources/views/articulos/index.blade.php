@extends('layouts.app')

@section('title', 'Artículos')

@section('content')
<div class="page-header"><div><h1>Artículos</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('articulos.create') }}" class="btn btn-primary mb-3">+ Nuevo artículo</a>
@endif

</div></div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <select name="ubicacion" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todas las ubicaciones --</option>
            @foreach($ubicaciones as $u)
                <option value="{{ $u->Id_Ubicacion }}" {{ request('ubicacion') == $u->Id_Ubicacion ? 'selected' : '' }}>{{ $u->Nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="categoria" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todas las categorías --</option>
            @foreach($categorias as $c)
                <option value="{{ $c->Id_Categoria }}" {{ request('categoria') == $c->Id_Categoria ? 'selected' : '' }}>{{ $c->Nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="tipo" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todos los tipos --</option>
            @foreach($tipos as $t)
                <option value="{{ $t->Id_Tipo }}" {{ request('tipo') == $t->Id_Tipo ? 'selected' : '' }}>{{ $t->Nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="empleado" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todos los empleados --</option>
            @foreach($empleados as $e)
                <option value="{{ $e->Id_Empleado }}" {{ request('empleado') == $e->Id_Empleado ? 'selected' : '' }}>{{ $e->Nombre }} {{ $e->Apellido_Paterno }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="estado" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todos los estados --</option>
            @foreach(['Bien', 'Reparacion', 'Dañado', 'Obsoleto'] as $e)
                <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
    </div>
</form>

<div class="table-wrap">
<table class="table table-bordered table-striped align-middle">
    <tr>
        <th>Imagen</th>
        <th>Descripción</th>
        <th>Marca / Modelo</th>
        <th>Categoría</th>
        <th>Ubicación</th>
        <th>Responsable</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    @foreach($articulos as $articulo)
    <tr>
        <td>
            @if($articulo->URL_Imagen)
                <img src="{{ route('articulos.imagen', $articulo) }}" alt="{{ $articulo->Descripcion }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22120%22 height=%2280%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23eef2f7%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2212%22%3ESin imagen%3C/text%3E%3C/svg%3E';" style="width: 52px; height: 52px; object-fit: cover; border-radius: 10px; border: 1px solid #dfe5ef;">
            @else
                <div style="width: 52px; height: 52px; border-radius: 10px; background: #edf2fb; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 11px;">Sin img</div>
            @endif
        </td>
        <td>{{ $articulo->Descripcion }}</td>
        <td>{{ $articulo->marca->Nombre }} / {{ $articulo->modelo->Nombre }}</td>
        <td>{{ $articulo->categoria->Nombre }}</td>
        <td>{{ $articulo->ubicacion->Nombre }}</td>
        <td>{{ $articulo->empleado->Nombre }} {{ $articulo->empleado->Apellido_Paterno }}</td>
        <td>
            <span class="badge bg-{{ $articulo->Estado === 'Bien' ? 'success' : ($articulo->Estado === 'Reparacion' ? 'warning' : 'danger') }}">
                {{ $articulo->Estado }}
            </span>
        </td>
        <td>
            <a href="{{ route('articulos.show', $articulo) }}" class="btn btn-sm btn-info">Ver</a>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('articulos.destroy', $articulo) }}" method="POST" style="display:inline" onsubmit="return agregarMotivo(this)">
                @csrf
                @method('DELETE')
                <input type="hidden" name="Motivo">
                <select name="Tipo_Articulo" class="form-select form-select-sm d-inline-block w-auto" required>
                    <option value="">Tipo</option>
                    <option value="En Proceso de Baja">En Proceso de Baja</option>
                    <option value="Baja">Baja</option>
                </select>
                <button type="submit" class="btn btn-sm btn-danger">Dar de baja</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $articulos->links() }}

<script>
function agregarMotivo(form) {
    const tipo = form.querySelector('select[name="Tipo_Articulo"]').value;
    if (!tipo) {
        alert('Selecciona el tipo de baja antes de continuar.');
        return false;
    }
    const motivo = prompt('¿Motivo de la baja? (opcional)');
    if (motivo === null) return false; // canceló
    form.querySelector('input[name="Motivo"]').value = motivo;
    return confirm('¿Confirmas dar de baja este artículo?');
}
</script>
@endsection
