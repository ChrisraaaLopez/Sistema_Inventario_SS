<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Tipo;
use App\Models\Ubicacion;
use App\Models\Empleado;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ArticuloController extends Controller
{
    public function index(Request $request)
{
    $articulos = Articulo::with(['marca', 'modelo', 'categoria', 'ubicacion', 'empleado'])
        ->where(function ($query) {
            $query->where('status', 'activo')->orWhere(function ($nestedQuery) {
                $nestedQuery->whereNull('status')->where('Estatus', 'Activo');
            });
        })
        ->when($request->ubicacion, fn($q) => $q->where('FkId_Ubicacion', $request->ubicacion))
        ->when($request->categoria, fn($q) => $q->where('FkId_Categoria', $request->categoria))
        ->when($request->tipo, fn($q) => $q->where('FkId_Tipo', $request->tipo))
        ->when($request->empleado, fn($q) => $q->where('FkId_Empleado', $request->empleado))
        ->when($request->estado, fn($q) => $q->where('Estado', $request->estado))
        ->orderBy('Descripcion')
        ->paginate(15)
        ->withQueryString();

    $ubicaciones = Ubicacion::orderBy('Nombre')->get();
    $categorias = Categoria::orderBy('Nombre')->get();
    $tipos = Tipo::orderBy('Nombre')->get();
    $empleados = Empleado::orderBy('Nombre')->get();

    return view('articulos.index', compact('articulos', 'ubicaciones', 'categorias', 'tipos', 'empleados'));
}

    public function create()
    {
        return view('articulos.create', $this->catalogos());
    }

    public function generarCodigo()
    {
        $maxNumero = 0;
        $registros = DB::table('articulos')->select('Codigo_Barras')->get();

        foreach ($registros as $registro) {
            if (!is_string($registro->Codigo_Barras)) {
                continue;
            }

            if (preg_match('/^INV-([0-9]{1,8})$/', $registro->Codigo_Barras, $coincidencia)) {
                $maxNumero = max($maxNumero, (int) $coincidencia[1]);
            }
        }

        $intento = $maxNumero + 1;
        $limite = 1000;

        for ($i = 0; $i < $limite; $i++) {
            $codigo = 'INV-' . str_pad((string) $intento, 6, '0', STR_PAD_LEFT);

            $existe = DB::table('articulos')
                ->where('Codigo_Barras', $codigo)
                ->orWhere('N_Serie', $codigo)
                ->exists();

            if (!$existe) {
                return response()->json(['codigo' => $codigo]);
            }

            $intento++;
        }

        return response()->json([
            'message' => 'No se pudo generar un código único después de 1000 intentos.',
        ], 500);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Descripcion' => 'required|string|max:200',
            'FkId_Marca' => ['required', 'exists:marcas,Id_Marca'],
            'FkId_Modelo' => ['required', Rule::exists('modelos', 'Id_Modelo')->where(fn ($query) => $query->where('FkId_Marca', $request->input('FkId_Marca')))],
            'Codigo_Barras' => 'nullable|string|max:120|unique:articulos,Codigo_Barras',
            'N_Serie' => 'nullable|string|max:30',
            'Color' => 'nullable|string|max:100',
            'FkId_Categoria' => ['required', 'exists:categorias,Id_Categoria'],
            'FkId_Tipo' => ['required', Rule::exists('tipos', 'Id_Tipo')->where(fn ($query) => $query->where('FkId_Categoria', $request->input('FkId_Categoria')))],
            'FkId_Ubicacion' => 'required|exists:ubicaciones,Id_Ubicacion',
            'FkId_Empleado' => 'required|exists:empleados,Id_Empleado',
            'FkId_Factura' => 'nullable|exists:facturas,Id_Factura',
            'Notas' => 'nullable|string|max:200',
            'Comentarios' => 'nullable|string|max:200',
            'Estado' => 'required|in:Bien,Reparacion,Dañado,Obsoleto',
            'Tipo_Articulo' => 'required|in:Capitalizable,No Capitalizable,Consumible,En Proceso de Baja,Baja',
            'imagen' => 'nullable|image|max:2048',
        ], [
            'FkId_Modelo.exists' => 'La marca seleccionada no coincide con el modelo elegido.',
            'FkId_Tipo.exists' => 'La categoría seleccionada no coincide con el tipo elegido.',
            'Codigo_Barras.unique' => 'Ese código ya está registrado en otro artículo.',
        ]);

        $data = $request->only(
            'Descripcion', 'Codigo_Barras', 'FkId_Marca', 'FkId_Modelo', 'N_Serie', 'Color',
            'FkId_Categoria', 'FkId_Tipo', 'FkId_Ubicacion', 'FkId_Empleado',
            'FkId_Factura', 'Notas', 'Comentarios', 'Estado', 'Tipo_Articulo'
        );

        if ($request->hasFile('imagen')) {
            $data['URL_Imagen'] = $request->file('imagen')->store('articulos', 'public');
        }

        Articulo::create($data);

        return redirect()->route('articulos.index')->with('ok', 'Artículo registrado correctamente');
    }

    /** Entrega la imagen mediante una ruta autenticada, compatible con instalaciones sin symlink. */
    public function imagen(Articulo $articulo)
    {
        $path = ltrim(str_replace('\\', '/', (string) $articulo->URL_Imagen), '/');
        abort_if($path === '' || str_contains($path, '..') || !Storage::disk('public')->exists($path), 404);
        return Storage::disk('public')->response($path, basename($path));
    }

    public function show(Articulo $articulo)
    {
        $articulo->load(['marca', 'modelo', 'categoria', 'tipo', 'ubicacion', 'empleado', 'factura']);
        return view('articulos.show', compact('articulo'));
    }

    public function edit(Articulo $articulo)
    {
        return view('articulos.edit', array_merge(['articulo' => $articulo], $this->catalogos()));
    }

    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'Descripcion' => 'required|string|max:200',
            'FkId_Marca' => ['required', 'exists:marcas,Id_Marca'],
            'FkId_Modelo' => ['required', Rule::exists('modelos', 'Id_Modelo')->where(fn ($query) => $query->where('FkId_Marca', $request->input('FkId_Marca')))],
            'Codigo_Barras' => 'nullable|string|max:120|unique:articulos,Codigo_Barras,'. $articulo->Id_Articulo .',Id_Articulo',
            'N_Serie' => 'nullable|string|max:30',
            'Color' => 'nullable|string|max:100',
            'FkId_Categoria' => ['required', 'exists:categorias,Id_Categoria'],
            'FkId_Tipo' => ['required', Rule::exists('tipos', 'Id_Tipo')->where(fn ($query) => $query->where('FkId_Categoria', $request->input('FkId_Categoria')))],
            'FkId_Ubicacion' => 'required|exists:ubicaciones,Id_Ubicacion',
            'FkId_Empleado' => 'required|exists:empleados,Id_Empleado',
            'FkId_Factura' => 'nullable|exists:facturas,Id_Factura',
            'Notas' => 'nullable|string|max:200',
            'Comentarios' => 'nullable|string|max:200',
            'Estado' => 'required|in:Bien,Reparacion,Dañado,Obsoleto',
            'Tipo_Articulo' => 'required|in:Capitalizable,No Capitalizable,Consumible,En Proceso de Baja,Baja',
            'imagen' => 'nullable|image|max:2048',
        ], [
            'FkId_Modelo.exists' => 'La marca seleccionada no coincide con el modelo elegido.',
            'FkId_Tipo.exists' => 'La categoría seleccionada no coincide con el tipo elegido.',
            'Codigo_Barras.unique' => 'Ese código ya está registrado en otro artículo.',
        ]);

        $data = $request->only(
            'Descripcion', 'Codigo_Barras', 'FkId_Marca', 'FkId_Modelo', 'N_Serie', 'Color',
            'FkId_Categoria', 'FkId_Tipo', 'FkId_Ubicacion', 'FkId_Empleado',
            'FkId_Factura', 'Notas', 'Comentarios', 'Estado', 'Tipo_Articulo'
        );

        if ($request->hasFile('imagen')) {
            $data['URL_Imagen'] = $request->file('imagen')->store('articulos', 'public');
        }

        $articulo->update($data);

        return redirect()->route('articulos.index')->with('ok', 'Artículo actualizado correctamente');
    }

    // RF-03: baja de artículo (marca estado, no borra el registro)
    public function destroy(Request $request, Articulo $articulo)
{
    $request->validate([
        'Motivo' => 'nullable|string|max:300',
        'Tipo_Articulo' => 'required|in:En Proceso de Baja,Baja',
    ]);

    DB::transaction(function () use ($request, $articulo) {
        $articulo->update([
            'Tipo_Articulo' => $request->input('Tipo_Articulo'),
            'Estatus' => 'Inactivo',
            'status' => 'inactivo',
        ]);

        \App\Models\ArticuloBaja::updateOrCreate(
            ['FkId_Articulo' => $articulo->Id_Articulo],
            [
                'FkId_Usuario' => auth()->id(),
                'Fecha_Baja' => now()->toDateString(),
                'Motivo' => $request->input('Motivo'),
            ]
        );
    });

    return redirect()->route('articulos.index')->with('ok', 'Artículo marcado como inactivo y registrado en el histórico de bajas');
}

    private function catalogos()
    {
        return [
            'marcas' => Marca::orderBy('Nombre')->get(),
            'categorias' => Categoria::orderBy('Nombre')->get(),
            'tipos' => Tipo::orderBy('Nombre')->get(),
            'ubicaciones' => Ubicacion::orderBy('Nombre')->get(),
            'empleados' => Empleado::orderBy('Nombre')->get(),
            'facturas' => Factura::orderBy('Folio')->get(),
        ];
    }
}