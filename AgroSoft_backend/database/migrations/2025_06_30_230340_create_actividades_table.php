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
        Schema::create('actividades', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('tipo_actividad_id');
            $table->text('descripcion');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedBigInteger('cultivo_id');
            $table->string('estado', 20)->default('PENDIENTE');
            $table->string('prioridad', 20)->default('MEDIA');
            $table->text('instrucciones_adicionales')->nullable();
            $table->timestamps();

            $table->foreign('tipo_actividad_id')
                ->references('id')
                ->on('tipo_actividades')
                ->onDelete('cascade');
            $table->foreign('cultivo_id')
                ->references('id')
                ->on('cultivos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
