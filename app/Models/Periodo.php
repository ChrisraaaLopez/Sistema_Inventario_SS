<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Periodo extends Model { protected $table='periodos'; protected $primaryKey='Id_Periodo'; public $timestamps=false; protected $fillable=['Nombre','Fecha_Inicio','Fecha_Cierre','Estado','status','Estatus']; protected $casts=['Fecha_Inicio'=>'date','Fecha_Cierre'=>'date']; public function resguardos(){return $this->hasMany(Resguardo::class,'FkId_Periodo','Id_Periodo');} }
