<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vehiculo extends Model { protected $table='vehiculos'; protected $primaryKey='Id_Vehiculo'; public $timestamps=false; protected $fillable=['Tipo_Vehiculo','Nombre','Marca','Modelo','Anio','Placas','Numero_Serie','Capacidad','Estatus','Observaciones']; }
