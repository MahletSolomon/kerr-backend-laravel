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
        Schema::create('job_completion_requests', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // Equivalent to `user_id` int
            $table->unsignedBigInteger('job_id'); // Equivalent to `job_id` int
            $table->text('image')->nullable(); // Equivalent to `image` text (nullable)
            $table->text('message')->nullable(); // Equivalent to `message` text (nullable)
            $table->timestamps(); // Adds `created_at` and `updated_at` columns

            // Indexes for optimization
            $table->index('user_id'); // Index for `user_id`
            $table->index('job_id'); // Index for `job_id`

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_completion_requests');
    }
};
