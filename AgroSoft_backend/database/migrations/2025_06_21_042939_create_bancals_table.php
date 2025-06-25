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
        Schema::create('bancals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 15)->unique();
            $table->decimal('tam_x', 5, 2)->nullable();
            $table->decimal('tam_y', 5, 2)->nullable();
            $table->decimal('latitud', 9, 6)->nullable();
            $table->decimal('longitud', 9, 6)->nullable();
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancals');
    }
};
