<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'Id_Empleado';
    public $timestamps = false;

    protected $fillable = [
        'N_Trabajador', 'Nombre', 'Apellido_Paterno', 'Apellido_Materno',
        'FkId_Puesto', 'FkId_Area', 'Estatus', 'status',
    ];

    public function setEstatusAttribute($value): void
    {
        $normalized = match (strtolower((string) $value)) {
            'baja', 'inactivo' => 'inactivo',
            'activo' => 'activo',
            default => strtolower((string) $value),
        };

        $this->attributes['Estatus'] = $normalized === 'inactivo' ? 'Inactivo' : 'Activo';
        $this->attributes['status'] = $normalized;
    }

    public function setStatusAttribute($value): void
    {
        $normalized = match (strtolower((string) $value)) {
            'baja', 'inactivo' => 'inactivo',
            'activo' => 'activo',
            default => strtolower((string) $value),
        };

        $this->attributes['status'] = $normalized;
        $this->attributes['Estatus'] = $normalized === 'inactivo' ? 'Inactivo' : 'Activo';
    }

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'FkId_Puesto');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'FkId_Area');
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'FkId_Empleado');
    }
}