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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // Equivalent to `user_id` int
            $table->string('post_title', 30); // Equivalent to `post_title` varchar(30)
            $table->string('post_caption', 255); // Equivalent to `post_caption` varchar(255)
            $table->text('post_thumbnail'); // Equivalent to `post_thumbnail` text NOT NULL
            $table->integer('view')->default(0); // Equivalent to `post_thumbnail` text NOT NULL
            $table->integer('save')->default(0); // Equivalent to `post_thumbnail` text NOT NULL
            $table->json('post_image'); // Equivalent to `post_image` json NOT NULL
            $table->timestamps(); // Adds `created_at` and `updated_at` columns

            // Index for `user_id` to optimize joins
            $table->index('user_id');

            // Foreign key constraint referencing `users` table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
