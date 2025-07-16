<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_sensores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('unidad_medida', 10);
            $table->decimal('medida_minima', 10, 2);
            $table->decimal('medida_maxima', 10, 2);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_sensores');
    }
};