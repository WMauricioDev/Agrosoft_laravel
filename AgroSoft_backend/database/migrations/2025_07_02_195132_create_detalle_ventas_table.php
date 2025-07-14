<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();

            // Relación con ventas
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');

            // Relación con productos (ajusta el nombre si la tabla es diferente)
            $table->foreignId('producto')->constrained('precio_productos');

            // Relación con unidad_medidas (ajusta el nombre si la tabla es diferente)
            $table->foreignId('unidad_medidas')->constrained('unidad_medidas');

            $table->integer('cantidad');
            $table->decimal('total', 10, 2);
            $table->decimal('precio_unitario', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
