<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('articulos', function(Blueprint $table){ $table->string('Codigo_Barras',120)->nullable()->unique()->after('N_Serie'); }); }
 public function down(): void { Schema::table('articulos', function(Blueprint $table){ $table->dropUnique(['Codigo_Barras']); $table->dropColumn('Codigo_Barras'); }); }
};
