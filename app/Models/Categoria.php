<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'Id_Categoria';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'status', 'Estatus'];

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

    public function tipos()
    {
        return $this->hasMany(Tipo::class, 'FkId_Categoria');
    }
}