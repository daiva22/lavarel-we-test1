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
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();

            // Link to user
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Link to service
            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            // Rating (1–5)
            $table->unsignedTinyInteger('rating');

            // Optional comment
            $table->text('comment')->nullable();

            // Timestamps
            $table->timestamps();

            // Prevent duplicate review
            $table->unique(['user_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
    }
};