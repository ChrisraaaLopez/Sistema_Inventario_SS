@extends('layouts.app')

@section('title', 'Nuevo tipo')

@section('content')
<div class="page-header"><h1>Nuevo tipo</h1></div>

<div class="card-form"><form action="{{ route('tipos.store') }}" method="POST" class="mt-3">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Categoría:</label>
        <select name="FkId_Categoria" class="form-select" required>
            <option value="">-- Selecciona una categoría --</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->Id_Categoria }}" {{ old('FkId_Categoria') == $categoria->Id_Categoria ? 'selected' : '' }}>
                    {{ $categoria->Nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            <option value="Activo" {{ old('Estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('Estatus') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('tipos.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection