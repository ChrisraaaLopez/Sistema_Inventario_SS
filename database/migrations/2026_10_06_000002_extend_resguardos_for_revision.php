<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('resguardos', function(Blueprint $table){
   $table->foreignId('FkId_Ubicacion')->nullable()->after('FkId_Empleado')->constrained('ubicaciones','Id_Ubicacion')->nullOnDelete()->cascadeOnUpdate();
   $table->dateTime('Fecha_Cierre')->nullable()->after('Fecha_Firma');
   $table->string('Observaciones',500)->nullable()->after('Cerrado');
 }); }
 public function down(): void { Schema::table('resguardos', function(Blueprint $table){ $table->dropForeign(['FkId_Ubicacion']); $table->dropColumn(['FkId_Ubicacion','Fecha_Cierre','Observaciones']); }); }
};
