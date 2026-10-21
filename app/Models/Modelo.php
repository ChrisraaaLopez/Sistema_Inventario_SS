<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelos';
    protected $primaryKey = 'Id_Modelo';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'FkId_Marca', 'FkId_Tipo', 'URL_Imagen', 'Estatus', 'status'];

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

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'FkId_Marca');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'FkId_Tipo');
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'FkId_Modelo');
    }
}