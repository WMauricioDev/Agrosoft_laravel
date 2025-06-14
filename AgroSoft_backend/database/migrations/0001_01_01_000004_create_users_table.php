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
    $table->string('name');
    $table->unsignedBigInteger('rol_id')->default(4);
    $table->string('email')->unique();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();

    // Clave foránea
    $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
});

        DB::table('users')->insert([
        'name' => 'Adminer',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('123456'),
        'rol_id' => 1,  
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
