<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('Id_Rol');
            $table->string('Nombre', 30);
            $table->unique('Nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};