@extends('layouts.app')

@section('title', 'Nueva factura')

@section('content')
<div class="page-header"><h1>Nueva factura</h1></div>

<div class="card-form"><form action="{{ route('facturas.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
    @csrf

    <div class="mb-3">
        <label class="form-label">Folio:</label>
        <input type="text" name="Folio" value="{{ old('Folio') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha:</label>
        <input type="date" name="Fecha" value="{{ old('Fecha') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Proveedor:</label>
        <input type="text" name="Proveedor" value="{{ old('Proveedor') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Monto:</label>
        <input type="number" step="0.01" name="Monto" value="{{ old('Monto') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción (opcional):</label>
        <textarea name="Descripcion" class="form-control">{{ old('Descripcion') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">PDF de la factura (opcional, se puede subir después):</label>
        <input type="file" name="pdf" class="form-control" accept="application/pdf">
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection