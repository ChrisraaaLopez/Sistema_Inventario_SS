<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipos';
    protected $primaryKey = 'Id_Tipo';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'FkId_Categoria', 'Estatus', 'status'];

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

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'FkId_Categoria');
    }

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'FkId_Tipo');
    }
}