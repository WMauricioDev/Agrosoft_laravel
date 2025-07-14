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
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->text('descripcion');
            $table->unsignedInteger('cantidad')->default(1);
            $table->unsignedBigInteger('unidad_medida_id')->nullable();
            $table->unsignedBigInteger('tipo_insumo_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('tipo_empacado', 100)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->date('fecha_caducidad')->nullable();
            $table->decimal('precio_insumo', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('unidad_medida_id')
                ->references('id')
                ->on('unidad_medidas')
                ->onDelete('restrict');
            $table->foreign('tipo_insumo_id')
                ->references('id')
                ->on('tipo_insumos')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
