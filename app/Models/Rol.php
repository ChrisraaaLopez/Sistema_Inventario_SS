<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Rol extends Model {
    protected $table='roles'; protected $primaryKey='Id_Rol'; public $timestamps=false; protected $fillable=['Nombre'];
    public function usuarios(): HasMany { return $this->hasMany(Usuario::class,'FkId_Rol','Id_Rol'); }
}
