@extends('layouts.app')

@section('title', 'Nuevo puesto')

@section('content')
<div class="page-header"><h1>Nuevo puesto</h1></div>

<div class="card-form"><form action="{{ route('puestos.store') }}" method="POST" class="mt-3">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre') }}" class="form-control" required placeholder="Ej. Coordinador de Laboratorio">
    </div>
    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            <option value="Activo" {{ old('Estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('Estatus') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('puestos.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection