<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('residuos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cosecha')->constrained('cosechas')->onDelete('cascade');
            $table->foreignId('id_tipo_residuo')->constrained('tipo_residuos')->onDelete('restrict');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->decimal('cantidad', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residuos');
    }
};
