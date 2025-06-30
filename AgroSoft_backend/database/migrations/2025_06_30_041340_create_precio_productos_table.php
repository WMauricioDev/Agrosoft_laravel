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
        Schema::create('precio_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cosecha_id')->nullable();
            $table->unsignedBigInteger('unidad_medida_id')->nullable();
            $table->decimal('precio', 10, 2);
            $table->date('fecha_registro');
            $table->integer('stock')->default(0);
            $table->date('fecha_caducidad')->nullable();
            $table->timestamps();

            $table->foreign('cosecha_id')
                ->references('id')
                ->on('cosechas')
                ->onDelete('cascade');
            $table->foreign('unidad_medida_id')
                ->references('id')
                ->on('unidad_medidas')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_productos');
    }
};
