@extends('layouts.app')

@section('title', 'Marcas')

@section('content')
<div class="page-header"><div><h1>Marcas</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('marcas.create') }}" class="btn btn-primary mb-3">+ Nueva marca</a>
@endif

</div></div>

<div class="table-wrap">
<table class="table table-bordered table-striped">
    <tr>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    @foreach($marcas as $marca)
    <tr>
        <td>{{ $marca->Nombre }}</td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('marcas.destroy', $marca) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta marca?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $marcas->links() }}
@endsection
