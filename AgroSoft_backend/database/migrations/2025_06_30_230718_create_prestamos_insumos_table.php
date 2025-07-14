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
        Schema::create('prestamos_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actividad_id');
            $table->unsignedBigInteger('insumo_id');
            $table->integer('cantidad_usada')->default(0);
            $table->integer('cantidad_devuelta')->default(0);
            $table->dateTime('fecha_devolucion')->nullable();
            $table->unsignedBigInteger('unidad_medida_id')->nullable();
            $table->timestamps();

            $table->foreign('actividad_id')
                ->references('id')
                ->on('actividades')
                ->onDelete('cascade');
            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
                ->onDelete('cascade');
            $table->foreign('unidad_medida_id')
                ->references('id')
                ->on('unidad_medidas')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos_insumos');
    }
};
