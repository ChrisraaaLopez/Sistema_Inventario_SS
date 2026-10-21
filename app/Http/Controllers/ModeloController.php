<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Models\Marca;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModeloController extends Controller
{
    public function index()
    {
        $modelos = Modelo::with(['marca', 'tipo'])->where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })->orderBy('Nombre')->paginate(15);
        return view('modelos.index', compact('modelos'));
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('modelos', 'Nombre')->where(fn ($query) => $query->where('FkId_Marca', $request->input('FkId_Marca')))],
            'FkId_Marca' => 'required|exists:marcas,Id_Marca',
            'FkId_Tipo' => 'required|exists:tipos,Id_Tipo',
        ]);
        $modelo = Modelo::updateOrCreate(['Nombre' => trim($data['Nombre']), 'FkId_Marca' => $data['FkId_Marca']], ['FkId_Tipo' => $data['FkId_Tipo'], 'Estatus' => 'Activo', 'status' => 'activo']);
        return response()->json(['Id_Modelo' => $modelo->Id_Modelo, 'Nombre' => $modelo->Nombre]);
    }

    public function create()
    {
        $marcas = Marca::where('Estatus', 'Activo')->orderBy('Nombre')->get();
        $tipos = Tipo::where('Estatus', 'Activo')->orderBy('Nombre')->get();
        return view('modelos.create', compact('marcas', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('modelos', 'Nombre')->where(fn ($query) => $query->where('FkId_Marca', $request->input('FkId_Marca')))],
            'FkId_Marca' => 'required|exists:marcas,Id_Marca',
            'FkId_Tipo' => 'required|exists:tipos,Id_Tipo',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('Nombre', 'FkId_Marca', 'FkId_Tipo');
        $data['Estatus'] = 'Activo';
        $data['status'] = 'activo';

        if ($request->hasFile('imagen')) {
            $data['URL_Imagen'] = $request->file('imagen')->store('modelos', 'public');
        }

        Modelo::create($data);

        return redirect()->route('modelos.index')->with('ok', 'Modelo registrado correctamente');
    }

    public function edit(Modelo $modelo)
    {
        $marcas = Marca::where('Estatus', 'Activo')->orderBy('Nombre')->get();
        $tipos = Tipo::where('Estatus', 'Activo')->orderBy('Nombre')->get();
        return view('modelos.edit', compact('modelo', 'marcas', 'tipos'));
    }

    public function update(Request $request, Modelo $modelo)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:100', Rule::unique('modelos', 'Nombre')->where(fn ($query) => $query->where('FkId_Marca', $request->input('FkId_Marca')))->ignore($modelo->Id_Modelo, 'Id_Modelo')],
            'FkId_Marca' => 'required|exists:marcas,Id_Marca',
            'FkId_Tipo' => 'required|exists:tipos,Id_Tipo',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('Nombre', 'FkId_Marca', 'FkId_Tipo');

        if ($request->hasFile('imagen')) {
            $data['URL_Imagen'] = $request->file('imagen')->store('modelos', 'public');
        }

        $modelo->update($data);

        return redirect()->route('modelos.index')->with('ok', 'Modelo actualizado correctamente');
    }

    public function destroy(Modelo $modelo)
    {
        $modelo->update(['Estatus' => 'Inactivo', 'status' => 'inactivo']);
        return redirect()->route('modelos.index')->with('ok', 'Modelo dado de baja correctamente');
    }

    // RF-04: filtro dependiente marca -> modelo, usado por el formulario de Artículos más adelante
    public function porMarca(Marca $marca)
    {
        return $marca->modelos()->where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })->select('Id_Modelo', 'Nombre')->orderBy('Nombre')->get();
    }
}