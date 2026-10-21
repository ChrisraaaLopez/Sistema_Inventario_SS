<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'Id_Usuario';
    public $timestamps = false;
    protected $fillable = ['Nombre_Usuario', 'Password_Hash', 'FkId_Rol', 'FkId_Empleado', 'Estatus', 'Fecha_Creacion', 'Ultimo_Acceso'];
    protected $hidden = ['Password_Hash'];

    public function getAuthPasswordName(): string { return 'Password_Hash'; }
    public function getAuthPassword(): string { return (string) $this->Password_Hash; }
    public function rol(): BelongsTo { return $this->belongsTo(Rol::class, 'FkId_Rol', 'Id_Rol'); }
    public function empleado(): BelongsTo { return $this->belongsTo(Empleado::class, 'FkId_Empleado', 'Id_Empleado'); }
}
