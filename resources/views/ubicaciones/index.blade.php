@extends('layouts.app')

@section('title', 'Ubicaciones')

@section('content')
<div class="page-header"><div><h1>Ubicaciones</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('ubicaciones.create') }}" class="btn btn-primary mb-3">+ Nueva ubicación</a>
@endif

</div></div>

<div class="table-wrap">
<table class="table table-bordered table-striped">
    <tr>
        <th>Nombre</th>
        <th>Edificio</th>
        <th>Planta</th>
        <th>Acciones</th>
    </tr>
    @foreach($ubicaciones as $ubicacion)
    <tr>
        <td>{{ $ubicacion->Nombre }}</td>
        <td>{{ $ubicacion->Edificio }}</td>
        <td>{{ $ubicacion->Planta }}</td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta ubicación?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $ubicaciones->links() }}
@endsection
