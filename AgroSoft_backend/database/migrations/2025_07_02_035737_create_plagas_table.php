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
        Schema::create('plagas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_tipo_plaga');
            $table->string('nombre', 50);
            $table->text('descripcion');
            $table->string('img')->nullable();
            $table->timestamps();

            // Clave foránea
            $table->foreign('fk_tipo_plaga')->references('id')->on('tipo_plagas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plagas');
    }
};