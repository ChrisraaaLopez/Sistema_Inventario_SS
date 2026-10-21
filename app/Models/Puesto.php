<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $table = 'puestos';
    protected $primaryKey = 'Id_Puesto';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Estatus', 'status'];

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

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'FkId_Puesto');
    }
}