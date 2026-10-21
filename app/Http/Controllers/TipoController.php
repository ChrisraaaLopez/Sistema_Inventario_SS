<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoController extends Controller
{
    public function index()
    {
        $tipos = Tipo::with('categoria')->where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })->orderBy('Nombre')->paginate(15);
        return view('tipos.index', compact('tipos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('Nombre')->get();
        return view('tipos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:200', Rule::unique('tipos', 'Nombre')->where(fn ($query) => $query->where('FkId_Categoria', $request->input('FkId_Categoria')))],
            'FkId_Categoria' => 'required|exists:categorias,Id_Categoria',
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'FkId_Categoria', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        Tipo::create($data);

        return redirect()->route('tipos.index')->with('ok', 'Tipo registrado correctamente');
    }

    public function edit(Tipo $tipo)
    {
        $categorias = Categoria::orderBy('Nombre')->get();
        return view('tipos.edit', compact('tipo', 'categorias'));
    }

    public function update(Request $request, Tipo $tipo)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:200', Rule::unique('tipos', 'Nombre')->where(fn ($query) => $query->where('FkId_Categoria', $request->input('FkId_Categoria')))->ignore($tipo->Id_Tipo, 'Id_Tipo')],
            'FkId_Categoria' => 'required|exists:categorias,Id_Categoria',
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'FkId_Categoria', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        $tipo->update($data);

        return redirect()->route('tipos.index')->with('ok', 'Tipo actualizado correctamente');
    }

    public function destroy(Tipo $tipo)
    {
        $tipo->update(['Estatus' => 'Inactivo', 'status' => 'inactivo']);
        return redirect()->route('tipos.index')->with('ok', 'Tipo dado de baja correctamente');
    }
}