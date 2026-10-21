<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $primaryKey = 'Id_Factura';
    public $timestamps = false;

    protected $fillable = ['Folio', 'Fecha', 'Proveedor', 'Monto', 'Descripcion', 'URL_Factura', 'status', 'Estatus'];

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

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'FkId_Factura');
    }
}