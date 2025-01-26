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
        Schema::create('chats', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('user_1_id'); // Equivalent to `user_1_id` int
            $table->unsignedBigInteger('user_2_id'); // Equivalent to `user_2_id` int
            $table->text('last_sent_message')->nullable();
            $table->timestamp('created_at')->useCurrent(); // Equivalent to `created_at` timestamp NOT NULL
            $table->timestamp('last_sent_time')->useCurrent(); // Equivalent to `created_at` timestamp NOT NULL

            // Indexes for optimization
            $table->index('user_1_id'); // Index for `user_1_id`
            $table->index('user_2_id'); // Index for `user_2_id`

            // Foreign key constraints
            $table->foreign('user_1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_2_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
