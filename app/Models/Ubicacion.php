<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $primaryKey = 'Id_Ubicacion';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Edificio', 'Planta', 'Estatus', 'status'];

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
        return $this->hasMany(Articulo::class, 'FkId_Ubicacion');
    }
}