@extends('layouts.app')

@section('title', 'Tipos')

@section('content')
<div class="page-header"><div><h1>Tipos</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('tipos.create') }}" class="btn btn-primary mb-3">+ Nuevo tipo</a>
@endif

</div></div>

<div class="table-wrap">
<table class="table table-bordered table-striped">
    <tr>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Acciones</th>
    </tr>
    @foreach($tipos as $tipo)
    <tr>
        <td>{{ $tipo->Nombre }}</td>
        <td>{{ $tipo->categoria->Nombre }}</td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('tipos.edit', $tipo) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('tipos.destroy', $tipo) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este tipo?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $tipos->links() }}
@endsection
