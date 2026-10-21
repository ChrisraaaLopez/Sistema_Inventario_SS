<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })->orderBy('Nombre')->paginate(15);
        return view('marcas.index', compact('marcas'));
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('marcas', 'Nombre')->ignore($request->input('Id_Marca'), 'Id_Marca')],
        ]);
        $marca = Marca::updateOrCreate(['Nombre' => trim($data['Nombre'])], ['Estatus' => 'Activo', 'status' => 'activo']);
        return response()->json(['Id_Marca' => $marca->Id_Marca, 'Nombre' => $marca->Nombre]);
    }

    public function create()
    {
        return view('marcas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('marcas', 'Nombre')],
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        Marca::create($data);

        return redirect()->route('marcas.index')->with('ok', 'Marca registrada correctamente');
    }

    public function edit(Marca $marca)
    {
        return view('marcas.edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('marcas', 'Nombre')->ignore($marca->Id_Marca, 'Id_Marca')],
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        $marca->update($data);

        return redirect()->route('marcas.index')->with('ok', 'Marca actualizada correctamente');
    }

    public function destroy(Marca $marca)
    {
        $marca->update(['Estatus' => 'Inactivo', 'status' => 'inactivo']);
        return redirect()->route('marcas.index')->with('ok', 'Marca dada de baja correctamente');
    }
}