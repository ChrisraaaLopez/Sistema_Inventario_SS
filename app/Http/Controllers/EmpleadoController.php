<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Puesto;
use App\Models\Area;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $estatus = $request->input('estatus', 'Activo');
        $query = Empleado::with(['puesto', 'area']);

        if ($estatus === 'Todos') {
            $query = $query->orderBy('Nombre');
        } else {
            $query = $query->where(function ($innerQuery) use ($estatus) {
                $innerQuery->where('Estatus', ucfirst(strtolower($estatus)))->orWhere('status', strtolower($estatus));
            })->orderBy('Nombre');
        }

        $empleados = $query->paginate(15)->withQueryString();
        return view('empleados.index', compact('empleados', 'estatus'));
    }

    public function create()
    {
        $puestos = Puesto::orderBy('Nombre')->get();
        $areas = Area::orderBy('Nombre')->get();
        return view('empleados.create', compact('puestos', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'N_Trabajador' => 'required|string|max:10|unique:empleados,N_Trabajador',
            'Nombre' => 'required|string|max:50',
            'Apellido_Paterno' => 'required|string|max:50',
            'Apellido_Materno' => 'nullable|string|max:50',
            'FkId_Puesto' => 'required|exists:puestos,Id_Puesto',
            'FkId_Area' => 'required|exists:areas,Id_Area',
            'Estatus' => 'required|in:Activo,Inactivo,Baja',
        ]);

        $data = $request->only(
            'N_Trabajador', 'Nombre', 'Apellido_Paterno', 'Apellido_Materno',
            'FkId_Puesto', 'FkId_Area', 'Estatus'
        );
        $data['Estatus'] = $data['Estatus'] === 'Baja' ? 'Inactivo' : $data['Estatus'];
        $data['status'] = strtolower($data['Estatus']);

        Empleado::create($data);

        return redirect()->route('empleados.index')->with('ok', 'Empleado registrado correctamente');
    }

    public function edit(Empleado $empleado)
    {
        $puestos = Puesto::orderBy('Nombre')->get();
        $areas = Area::orderBy('Nombre')->get();
        return view('empleados.edit', compact('empleado', 'puestos', 'areas'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'N_Trabajador' => 'required|string|max:10|unique:empleados,N_Trabajador,' . $empleado->Id_Empleado . ',Id_Empleado',
            'Nombre' => 'required|string|max:50',
            'Apellido_Paterno' => 'required|string|max:50',
            'Apellido_Materno' => 'nullable|string|max:50',
            'FkId_Puesto' => 'required|exists:puestos,Id_Puesto',
            'FkId_Area' => 'required|exists:areas,Id_Area',
            'Estatus' => 'required|in:Activo,Inactivo,Baja',
        ]);

        $data = $request->only(
            'N_Trabajador', 'Nombre', 'Apellido_Paterno', 'Apellido_Materno',
            'FkId_Puesto', 'FkId_Area', 'Estatus'
        );
        $data['Estatus'] = $data['Estatus'] === 'Baja' ? 'Inactivo' : $data['Estatus'];
        $data['status'] = strtolower($data['Estatus']);

        $empleado->update($data);

        return redirect()->route('empleados.index')->with('ok', 'Empleado actualizado correctamente');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->update(['Estatus' => 'Inactivo', 'status' => 'inactivo']);
        return redirect()->route('empleados.index')->with('ok', 'Empleado desactivado');
    }
}