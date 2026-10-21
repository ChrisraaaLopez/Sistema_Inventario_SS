<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::orderBy('Fecha', 'desc')->paginate(15);
        return view('facturas.index', compact('facturas'));
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'Folio' => 'required|string|max:50|unique:facturas,Folio',
            'Fecha' => 'required|date',
            'Proveedor' => 'required|string|max:100',
            'Monto' => 'required|numeric|min:0',
            'Descripcion' => 'nullable|string|max:500',
        ]);
        $data['URL_Factura'] = '';
        $factura = Factura::create($data);
        return response()->json(['Id_Factura' => $factura->Id_Factura, 'Folio' => $factura->Folio, 'Proveedor' => $factura->Proveedor]);
    }

    public function create()
    {
        return view('facturas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Folio' => 'required|string|max:50|unique:facturas,Folio',
            'Fecha' => 'required|date',
            'Proveedor' => 'required|string|max:100',
            'Monto' => 'required|numeric|min:0',
            'Descripcion' => 'nullable|string|max:500',
            'pdf' => 'nullable|file|mimes:pdf|max:8192',
        ]);

        $data = $request->only('Folio', 'Fecha', 'Proveedor', 'Monto', 'Descripcion');

        if ($request->hasFile('pdf')) {
            $data['URL_Factura'] = $request->file('pdf')->store('facturas', 'public');
        } else {
            $data['URL_Factura'] = '';
        }

        Factura::create($data);

        return redirect()->route('facturas.index')->with('ok', 'Factura registrada correctamente');
    }

    /** Sirve el PDF desde una ruta autenticada para evitar errores 403 del enlace simbólico public/storage. */
    public function archivo(Factura $factura): Response
    {
        $path = ltrim(str_replace('\\', '/', (string) $factura->URL_Factura), '/');
        abort_if($path === '' || str_contains($path, '..') || !Storage::disk('public')->exists($path), 404, 'El PDF de esta factura no existe en el almacenamiento.');
        return Storage::disk('public')->response($path, basename($path), ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . basename($path) . '"']);
    }

    public function show(Factura $factura)
    {
        return view('facturas.show', compact('factura'));
    }

    public function edit(Factura $factura)
    {
        return view('facturas.edit', compact('factura'));
    }

    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'Folio' => 'required|string|max:50|unique:facturas,Folio,' . $factura->Id_Factura . ',Id_Factura',
            'Fecha' => 'required|date',
            'Proveedor' => 'required|string|max:100',
            'Monto' => 'required|numeric|min:0',
            'Descripcion' => 'nullable|string|max:500',
            'pdf' => 'nullable|file|mimes:pdf|max:8192',
        ]);

        $data = $request->only('Folio', 'Fecha', 'Proveedor', 'Monto', 'Descripcion');

        if ($request->hasFile('pdf')) {
            $data['URL_Factura'] = $request->file('pdf')->store('facturas', 'public');
        }

        $factura->update($data);

        return redirect()->route('facturas.index')->with('ok', 'Factura actualizada correctamente');
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();
        return redirect()->route('facturas.index')->with('ok', 'Factura eliminada');
    }
}