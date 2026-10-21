<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloBaja extends Model
{
    protected $table = 'articulos_bajas';
    protected $primaryKey = 'Id_Baja';
    public $timestamps = false;

    protected $fillable = ['FkId_Articulo', 'Fecha_Baja', 'Motivo', 'FkId_Usuario'];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'FkId_Articulo');
    }
}