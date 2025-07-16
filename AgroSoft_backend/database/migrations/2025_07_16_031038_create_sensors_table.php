<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->foreignId('tipo_sensor_id')->constrained('tipo_sensores')->cascadeOnDelete();
            $table->text('descripcion')->nullable();
            $table->foreignId('bancal_id')->nullable()->constrained('bancals')->cascadeOnDelete();
            $table->decimal('medida_minima', 10, 2)->default(0);
            $table->decimal('medida_maxima', 10, 2)->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('inactivo');
            $table->string('device_code', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};