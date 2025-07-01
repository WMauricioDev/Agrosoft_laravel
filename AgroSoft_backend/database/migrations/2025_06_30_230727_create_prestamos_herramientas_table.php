<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestamos_herramientas', function (Blueprint $table) {
         $table->id();
            $table->unsignedBigInteger('actividad_id');
            $table->unsignedBigInteger('herramienta_id');
            $table->unsignedBigInteger('bodega_herramienta_id')->nullable();
            $table->integer('cantidad_entregada')->default(1);
            $table->integer('cantidad_devuelta')->default(0);
            $table->boolean('entregada')->default(true);
            $table->boolean('devuelta')->default(false);
            $table->dateTime('fecha_devolucion')->nullable();
            $table->timestamps();

            $table->foreign('actividad_id')
                ->references('id')
                ->on('actividades')
                ->onDelete('cascade');
            $table->foreign('herramienta_id')
                ->references('id')
                ->on('herramientas')
                ->onDelete('cascade');
            $table->foreign('bodega_herramienta_id')
                ->references('id')
                ->on('bodega_herramientas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos_herramientas');
    }
};
