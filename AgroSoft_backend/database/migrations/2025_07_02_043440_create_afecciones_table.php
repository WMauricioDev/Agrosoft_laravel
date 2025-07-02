<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('afecciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->date('fecha_deteccion');
            $table->enum('gravedad', ['L', 'M', 'G']);
            $table->enum('estado', ['AC', 'ST', 'EC', 'EL'])->default('AC');
            $table->unsignedBigInteger('plaga_id');
            $table->unsignedBigInteger('cultivo_id');
            $table->unsignedBigInteger('bancal_id');
            $table->timestamps();

            $table->foreign('plaga_id')->references('id')->on('plagas')->onDelete('restrict');
            $table->foreign('cultivo_id')->references('id')->on('cultivos')->onDelete('restrict');
            $table->foreign('bancal_id')->references('id')->on('bancals')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('afecciones');
    }
};