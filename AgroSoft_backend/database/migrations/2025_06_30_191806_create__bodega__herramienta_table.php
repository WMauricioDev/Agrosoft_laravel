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
        Schema::create('bodega_herramientas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bodega_id');
            $table->unsignedBigInteger('herramienta_id');
            $table->unsignedInteger('cantidad')->default(1);
            $table->unsignedBigInteger('creador_id')->nullable();
            $table->decimal('costo_total', 10, 2)->nullable();
            $table->unsignedInteger('cantidad_prestada')->default(0);
            $table->timestamps();

            $table->foreign('bodega_id')
                ->references('id')
                ->on('bodegas')
                ->onDelete('cascade');
            $table->foreign('herramienta_id')
                ->references('id')
                ->on('herramientas')
                ->onDelete('cascade');
            $table->foreign('creador_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bodega_herramientas');
    }
};