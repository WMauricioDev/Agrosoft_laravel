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
        Schema::create('bodega_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bodega_id');
            $table->unsignedBigInteger('insumo_id');
            $table->unsignedInteger('cantidad')->default(1);
            $table->timestamps();

            $table->foreign('bodega_id')
                ->references('id')
                ->on('bodegas')
                ->onDelete('cascade');
            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bodega_insumos');
    }
};
