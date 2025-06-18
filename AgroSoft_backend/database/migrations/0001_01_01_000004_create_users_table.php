<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string("apellido");
    $table->unsignedBigInteger('rol_id')->default(1);
    $table->string('email')->unique();
    $table->unsignedBigInteger('numero_documento')->unique();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();

    // Clave foránea
    $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
});

        DB::table('users')->insert([
        'nombre' => 'Adminer',
        'apellido'=>'userauth',
        'email' => 'admin@gmail.com',
        'numero_documento'=> 123456,
        'password' => Hash::make('admin'),
        'rol_id' => 4,  
        'created_at' => now(),
        'updated_at' => now(),
    ]);



        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
