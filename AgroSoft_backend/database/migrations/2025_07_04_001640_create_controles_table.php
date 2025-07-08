<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('controles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('afeccion_id')->constrained('afecciones')->cascadeOnDelete();
            $table->foreignId('tipo_control_id')->constrained('tipo_controles')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('insumos')->cascadeOnDelete();
            $table->string('descripcion', 255);
            $table->date('fecha_control');
            $table->foreignId('responsable_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('efectividad', 5, 2);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controles');
    }
};
