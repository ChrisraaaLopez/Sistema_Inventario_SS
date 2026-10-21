@extends('layouts.app')

@section('title', 'Modelos')

@section('content')
<div class="page-header"><div><h1>Modelos</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('modelos.create') }}" class="btn btn-primary mb-3">+ Nuevo modelo</a>
@endif

</div></div>

<div class="table-wrap">
<table class="table table-bordered table-striped align-middle">
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Marca</th>
        <th>Tipo</th>
        <th>Acciones</th>
    </tr>
    @foreach($modelos as $modelo)
    <tr>
        <td>
            @if($modelo->URL_Imagen)
                <img src="{{ Storage::url($modelo->URL_Imagen) }}" width="60" loading="lazy" alt="{{ $modelo->Nombre }}" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2260%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23eef2f7%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2210%22%3ESin imagen%3C/text%3E%3C/svg%3E';">
            @else
                <span class="text-muted">Sin imagen</span>
            @endif
        </td>
        <td>{{ $modelo->Nombre }}</td>
        <td>{{ $modelo->marca->Nombre }}</td>
        <td>{{ $modelo->tipo->Nombre }}</td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('modelos.edit', $modelo) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('modelos.destroy', $modelo) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este modelo?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $modelos->links() }}
@endsection
