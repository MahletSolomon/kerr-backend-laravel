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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // Equivalent to `user_id` int
            $table->timestamp('created_at')->useCurrent(); // Equivalent to `created_at` timestamp NOT NULL
            $table->string('job_title', 30); // Equivalent to `job_title` varchar(30) NOT NULL
            $table->text('job_description'); // Equivalent to `job_description` text NOT NULL
            $table->decimal('job_price', 10, 2); // Equivalent to `job_price` decimal(10,2) NOT NULL
            $table->boolean('job_negotiation'); // Equivalent to `job_negotiation` bool NOT NULL
            $table->boolean('job_public'); // Equivalent to `job_public` bool NOT NULL
            $table->json('job_detail'); // Equivalent to `job_detail` json NOT NULL
            $table->tinyInteger('job_state')->default(1); // Equivalent to `job_state` int1 default 1

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
        Schema::dropIfExists('jobs');
    }
};
