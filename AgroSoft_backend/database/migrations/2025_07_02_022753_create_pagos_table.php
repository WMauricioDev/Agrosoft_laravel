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
    Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('salario_id')->constrained('salarios')->onDelete('restrict');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('horas_trabajadas', 10, 2)->default(0);
            $table->decimal('jornales', 10, 2)->default(0);
            $table->decimal('total_pago', 10, 2)->default(0);
            $table->timestamp('fecha_calculo')->useCurrent();
            $table->timestamps();
        });

        Schema::create('actividad_pago', function (Blueprint $table) {
            $table->foreignId('pago_id')->constrained('pagos')->onDelete('cascade');
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->primary(['pago_id', 'actividad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_pago');
        Schema::dropIfExists('pagos');
    }
};
