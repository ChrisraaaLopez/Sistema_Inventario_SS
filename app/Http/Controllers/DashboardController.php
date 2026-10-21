<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Empleado;
use App\Models\Marca;
use App\Models\Factura;
use App\Models\Periodo;
use App\Models\Resguardo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totales = [
            'articulos' => Articulo::count(),
            'bien' => Articulo::where('Estado', 'Bien')->where('status', 'activo')->count(),
            'bajas' => Articulo::whereIn('Tipo_Articulo', ['En Proceso de Baja', 'Baja'])->count(),
            'empleados' => Empleado::where('Estatus', 'Activo')->count(),
            'marcas' => Marca::count(),
            'facturas' => Factura::count(),
            'resguardos_abiertos' => Resguardo::where('Cerrado', false)->count(),
        ];
        $periodoActivo = Periodo::where('Estado', 'Abierto')->orderByDesc('Id_Periodo')->first();
        $periodosRecientes = Periodo::withCount('resguardos')->orderByDesc('Id_Periodo')->limit(5)->get();
        $articulosPorEstado = Articulo::select('Estado', DB::raw('COUNT(*) as total'))
            ->groupBy('Estado')->orderBy('Estado')->get();
        return view('dashboard', compact('totales', 'periodoActivo', 'periodosRecientes', 'articulosPorEstado'));
    }
}
