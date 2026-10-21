<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';
    protected $primaryKey = 'Id_Articulo';
    public $timestamps = false;

    protected $fillable = [
        'Descripcion', 'Codigo_Barras', 'FkId_Marca', 'FkId_Modelo', 'N_Serie', 'Color',
        'FkId_Categoria', 'FkId_Tipo', 'FkId_Ubicacion', 'FkId_Empleado',
        'FkId_Factura', 'Notas', 'Comentarios', 'Estado', 'Tipo_Articulo', 'URL_Imagen',
        'status', 'Estatus',
    ];

    public function setEstatusAttribute($value): void
    {
        $this->attributes['Estatus'] = $value;
        $this->attributes['status'] = is_string($value) ? strtolower($value) : $value;
    }

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = $value;
        $this->attributes['Estatus'] = is_string($value) ? ucfirst(strtolower($value)) : $value;
    }

    public function marca()      { return $this->belongsTo(Marca::class, 'FkId_Marca'); }
    public function modelo()     { return $this->belongsTo(Modelo::class, 'FkId_Modelo'); }
    public function categoria()  { return $this->belongsTo(Categoria::class, 'FkId_Categoria'); }
    public function tipo()       { return $this->belongsTo(Tipo::class, 'FkId_Tipo'); }
    public function ubicacion()  { return $this->belongsTo(Ubicacion::class, 'FkId_Ubicacion'); }
    public function empleado()   { return $this->belongsTo(Empleado::class, 'FkId_Empleado'); }
    public function factura()    { return $this->belongsTo(Factura::class, 'FkId_Factura'); }
}