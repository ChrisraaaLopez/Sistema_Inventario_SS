@extends('layouts.app')

@section('title', 'Editar empleado')

@section('content')
<div class="page-header"><h1>Editar empleado</h1></div>

<div class="card-form"><form action="{{ route('empleados.update', $empleado) }}" method="POST" class="mt-3">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Número de trabajador:</label>
        <input type="text" name="N_Trabajador" value="{{ old('N_Trabajador', $empleado->N_Trabajador) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre', $empleado->Nombre) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellido paterno:</label>
        <input type="text" name="Apellido_Paterno" value="{{ old('Apellido_Paterno', $empleado->Apellido_Paterno) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellido materno:</label>
        <input type="text" name="Apellido_Materno" value="{{ old('Apellido_Materno', $empleado->Apellido_Materno) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Puesto:</label>
        <select name="FkId_Puesto" class="form-select" required>
            @foreach($puestos as $puesto)
                <option value="{{ $puesto->Id_Puesto }}" {{ old('FkId_Puesto', $empleado->FkId_Puesto) == $puesto->Id_Puesto ? 'selected' : '' }}>
                    {{ $puesto->Nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Área:</label>
        <select name="FkId_Area" class="form-select" required>
            @foreach($areas as $area)
                <option value="{{ $area->Id_Area }}" {{ old('FkId_Area', $empleado->FkId_Area) == $area->Id_Area ? 'selected' : '' }}>
                    {{ $area->Nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            @foreach(['Activo', 'Inactivo', 'Baja'] as $opcion)
                <option value="{{ $opcion }}" {{ old('Estatus', $empleado->Estatus) == $opcion ? 'selected' : '' }}>
                    {{ $opcion }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection