@extends('layouts.app')

@section('title', 'Puestos')

@section('content')
<div class="page-header"><div><h1>Puestos</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('puestos.create') }}" class="btn btn-primary mb-3">+ Nuevo puesto</a>
@endif

</div></div>

<div class="table-wrap">
<table class="table table-bordered table-striped">
    <tr>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    @foreach($puestos as $puesto)
    <tr>
        <td>{{ $puesto->Nombre }}</td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('puestos.destroy', $puesto) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este puesto?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $puestos->links() }}
@endsection
