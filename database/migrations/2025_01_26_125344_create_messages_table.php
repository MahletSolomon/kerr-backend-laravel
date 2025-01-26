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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // Equivalent to `user_id` int
            $table->unsignedBigInteger('chat_id'); // Equivalent to `chat_id` int
            $table->text('message_text')->nullable(); // Equivalent to `message_text` text (nullable)
            $table->json('message_image')->nullable(); // Equivalent to `message_image` json (nullable)
            $table->string('message_type', 10); // Equivalent to `message_type` varchar(10) NOT NULL

            // Indexes for optimization
            $table->index('user_id'); // Index for `user_id`
            $table->index('chat_id'); // Index for `chat_id`

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
