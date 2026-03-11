<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_diario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('cantidad_inicial');
            $table->integer('cantidad_disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_diario');
    }
};