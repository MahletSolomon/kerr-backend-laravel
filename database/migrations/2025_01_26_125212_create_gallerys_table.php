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
        Schema::create('gallerys', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // Equivalent to `user_id` int
            $table->unsignedBigInteger('post_id'); // Equivalent to `post_id` int
            $table->timestamps(); // Adds `created_at` and `updated_at` columns

            // Indexes for optimization
            $table->index('user_id'); // Index for `user_id`
            $table->index('post_id'); // Index for `post_id`

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallerys');
    }
};
