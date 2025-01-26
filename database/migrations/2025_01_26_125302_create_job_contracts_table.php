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
        Schema::create('job_contracts', function (Blueprint $table) {
            $table->id(); // Equivalent to `id` int auto_increment PRIMARY KEY
            $table->unsignedBigInteger('client_id'); // Equivalent to `client_id` int
            $table->unsignedBigInteger('freelance_id'); // Equivalent to `freelance_id` int
            $table->unsignedBigInteger('job_id'); // Equivalent to `job_id` int
            $table->tinyInteger('contract_state')->default(1); // Equivalent to `contract_state` int1 default 1
            $table->timestamp('created_at')->useCurrent(); // Equivalent to `created_at` timestamp NOT NULL

            // Indexes for optimization
            $table->index('client_id'); // Index for `client_id`
            $table->index('freelance_id'); // Index for `freelance_id`
            $table->index('job_id'); // Index for `job_id`

            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('freelance_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_contracts');
    }
};
