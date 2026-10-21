<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PuestoController extends Controller
{
    public function index()
    {
        $puestos = Puesto::where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })->orderBy('Nombre')->paginate(15);
        return view('puestos.index', compact('puestos'));
    }

    public function create()
    {
        return view('puestos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('puestos', 'Nombre')],
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        Puesto::create($data);

        return redirect()->route('puestos.index')->with('ok', 'Puesto registrado correctamente');
    }

    public function edit(Puesto $puesto)
    {
        return view('puestos.edit', compact('puesto'));
    }

    public function update(Request $request, Puesto $puesto)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('puestos', 'Nombre')->ignore($puesto->Id_Puesto, 'Id_Puesto')],
            'Estatus' => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->only('Nombre', 'Estatus');
        $data['status'] = strtolower($data['Estatus']);

        $puesto->update($data);

        return redirect()->route('puestos.index')->with('ok', 'Puesto actualizado correctamente');
    }

    public function destroy(Puesto $puesto)
    {
        $puesto->update(['Estatus' => 'Inactivo', 'status' => 'inactivo']);
        return redirect()->route('puestos.index')->with('ok', 'Puesto dado de baja correctamente');
    }
}