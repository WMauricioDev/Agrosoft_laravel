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
        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->foreignId('bancal_id')->constrained('bancals')->onDelete('cascade');
            $table->string('nombre', 50)->unique();
            $table->foreignId('unidad_medida_id')->constrained('unidad_medidas')->onDelete('cascade');
            $table->boolean('activo');
            $table->date('fecha_siembra');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivos');
    }
};
