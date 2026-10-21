@extends('layouts.app')

@section('title', 'Nueva marca')

@section('content')
<div class="page-header"><h1>Nueva marca</h1></div>

<div class="card-form"><form action="{{ route('marcas.store') }}" method="POST" class="mt-3">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            <option value="Activo" {{ old('Estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('Estatus') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('marcas.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection