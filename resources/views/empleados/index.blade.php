@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
<div class="page-header"><div><h1>Empleados</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">+ Nuevo empleado</a>
@endif

</div></div>

<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-md-3">
        <label class="form-label mb-1">Estatus</label>
        <select name="estatus" class="form-select" onchange="this.form.submit()">
            @foreach(['Activo', 'Inactivo', 'Baja', 'Todos'] as $opcion)
                <option value="{{ $opcion }}" {{ request('estatus', 'Activo') == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
            @endforeach
        </select>
    </div>
</form>

<div class="table-wrap">
<table class="table table-bordered table-striped">
    <tr>
        <th>N° Trabajador</th>
        <th>Nombre completo</th>
        <th>Puesto</th>
        <th>Área</th>
        <th>Estatus</th>
        <th>Acciones</th>
    </tr>
    @foreach($empleados as $empleado)
    <tr>
        <td>{{ $empleado->N_Trabajador }}</td>
        <td>{{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }} {{ $empleado->Apellido_Materno }}</td>
        <td>{{ $empleado->puesto->Nombre }}</td>
        <td>{{ $empleado->area->Nombre }}</td>
        <td>
            <span class="badge bg-{{ $empleado->Estatus === 'Activo' ? 'success' : ($empleado->Estatus === 'Inactivo' ? 'warning' : 'danger') }}">
                {{ $empleado->Estatus }}
            </span>
        </td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este empleado?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $empleados->links() }}
@endsection
