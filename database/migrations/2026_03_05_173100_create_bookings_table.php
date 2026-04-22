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
       
Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('service_id')->constrained()->restrictOnDelete();

        $table->string('car_brand')->nullable();
        $table->string('car_model')->nullable();

        $table->dateTime('start_at'); // booking date+time
        $table->enum('status', ['pending','approved','rejected','cancelled','completed'])->default('pending');
        $table->text('notes')->nullable();

        $table->timestamps();

        // Prevent double-booking for same service & time
        $table->unique(['service_id', 'start_at']);
    });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
