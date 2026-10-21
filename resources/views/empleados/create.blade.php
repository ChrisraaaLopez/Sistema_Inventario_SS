@extends('layouts.app')

@section('title', 'Nuevo empleado')

@section('content')
<div class="page-header"><h1>Nuevo empleado</h1></div>

<div class="card-form"><form action="{{ route('empleados.store') }}" method="POST" class="mt-3">
    @csrf

    <div class="mb-3">
        <label class="form-label">Número de trabajador:</label>
        <input type="text" name="N_Trabajador" value="{{ old('N_Trabajador') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellido paterno:</label>
        <input type="text" name="Apellido_Paterno" value="{{ old('Apellido_Paterno') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellido materno:</label>
        <input type="text" name="Apellido_Materno" value="{{ old('Apellido_Materno') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Puesto:</label>
        <select name="FkId_Puesto" class="form-select" required>
            <option value="">-- Selecciona un puesto --</option>
            @foreach($puestos as $puesto)
                <option value="{{ $puesto->Id_Puesto }}" {{ old('FkId_Puesto') == $puesto->Id_Puesto ? 'selected' : '' }}>
                    {{ $puesto->Nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Área:</label>
        <select name="FkId_Area" class="form-select" required>
            <option value="">-- Selecciona un área --</option>
            @foreach($areas as $area)
                <option value="{{ $area->Id_Area }}" {{ old('FkId_Area') == $area->Id_Area ? 'selected' : '' }}>
                    {{ $area->Nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            <option value="Activo" {{ old('Estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('Estatus') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            <option value="Baja" {{ old('Estatus') == 'Baja' ? 'selected' : '' }}>Baja</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection