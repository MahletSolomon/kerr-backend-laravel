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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 30)->unique();
            $table->text('password');
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('last_name', 30);
            $table->text('profile_picture')->nullable();
            $table->integer('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('gender',6)->nullable();
            $table->string('location',30)->nullable();
            $table->string('industry',20)->nullable();
            $table->string('experience',20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
