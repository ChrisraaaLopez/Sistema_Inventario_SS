<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('Id_Factura');
            $table->string('Folio', 50)->unique();
            $table->date('Fecha');
            $table->string('Proveedor', 100);
            $table->decimal('Monto', 12, 2);
            $table->string('Descripcion', 500)->nullable();
            $table->string('URL_Factura', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};