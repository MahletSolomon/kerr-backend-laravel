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
        Schema::create('user_informations', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->integer('total_save')->default(0); // Equivalent to `total_save` int default 0
            $table->integer('total_rating')->default(0); // Equivalent to `total_rating` int default 0
            $table->decimal('average_rating', 10, 2)->default(0); // Equivalent to `average_rating` decimal(10,2) default 0
            $table->integer('success_rating')->default(0); // Equivalent to `success_rating` int default 0
            $table->integer('success_percentage')->default(0); // Equivalent to `success_rating` int default 0
            $table->integer('total_job')->default(0); // Equivalent to `total_job` int default 0
            $table->integer('total_job_completed')->default(0); // Equivalent to `total_job_completed` int default 0
            $table->unsignedBigInteger('user_id')->unique(); // Equivalent to `user_id` int unique
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key constraint
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_informations');
    }
};
