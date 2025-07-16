<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dato_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->nullable()->constrained('sensors')->cascadeOnDelete();
            $table->foreignId('bancal_id')->nullable()->constrained('bancals')->cascadeOnDelete();
            $table->decimal('temperatura', 10, 2)->nullable();
            $table->decimal('humedad_ambiente', 10, 2)->nullable();
            $table->decimal('luminosidad', 10, 2)->nullable();
            $table->decimal('lluvia', 5, 2)->nullable();
            $table->decimal('velocidad_viento', 5, 2)->nullable();
            $table->decimal('direccion_viento', 3, 0)->nullable();
            $table->decimal('humedad_suelo', 5, 2)->nullable();
            $table->decimal('ph_suelo', 4, 2)->nullable();
            $table->dateTime('fecha_promedio');
            $table->integer('cantidad_mediciones')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dato_historicos');
    }
};