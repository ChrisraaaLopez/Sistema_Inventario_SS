<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ResguardoArticulo extends Model { protected $table='resguardo_articulos'; protected $primaryKey='Id_Resguardo_Articulo'; public $timestamps=false; protected $fillable=['FkId_Resguardo','FkId_Articulo','FkId_Ubicacion','Revisado','Fecha_Revision','Notas','Comentarios']; protected $casts=['Revisado'=>'boolean','Fecha_Revision'=>'datetime']; public function resguardo(){return $this->belongsTo(Resguardo::class,'FkId_Resguardo','Id_Resguardo');} public function articulo(){return $this->belongsTo(Articulo::class,'FkId_Articulo','Id_Articulo');} public function ubicacion(){return $this->belongsTo(Ubicacion::class,'FkId_Ubicacion','Id_Ubicacion');} }
