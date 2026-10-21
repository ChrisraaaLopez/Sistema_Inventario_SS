<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UbicacionController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })->orderBy('Edificio')->orderBy('Nombre')->paginate(15);
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    public function create()
    {
        return view('ubicaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('ubicaciones', 'Nombre')->where(fn ($query) => $query->where('Edificio', $request->input('Edificio'))->where('Planta', $request->input('Planta')))],
            'Edificio' => 'required|string|max:10',
            'Planta' => 'required|string|max:10',
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'Edificio', 'Planta', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        Ubicacion::create($data);

        return redirect()->route('ubicaciones.index')->with('ok', 'Ubicación registrada correctamente');
    }

    public function edit(Ubicacion $ubicacion)
    {
        return view('ubicaciones.edit', compact('ubicacion'));
    }

    public function update(Request $request, Ubicacion $ubicacion)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('ubicaciones', 'Nombre')->where(fn ($query) => $query->where('Edificio', $request->input('Edificio'))->where('Planta', $request->input('Planta')))->ignore($ubicacion->Id_Ubicacion, 'Id_Ubicacion')],
            'Edificio' => 'required|string|max:10',
            'Planta' => 'required|string|max:10',
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'Edificio', 'Planta', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        $ubicacion->update($data);

        return redirect()->route('ubicaciones.index')->with('ok', 'Ubicación actualizada correctamente');
    }

    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->update(['Estatus' => 'Inactivo', 'status' => 'inactivo']);
        return redirect()->route('ubicaciones.index')->with('ok', 'Ubicación dada de baja correctamente');
    }
}