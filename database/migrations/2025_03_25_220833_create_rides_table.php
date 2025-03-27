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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            
            $table->string('start_location');
            $table->string('ending_location');
            $table->dateTime('start_time');
            $table->integer('available_seats');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->boolean('luggage_allowed')->default(true);
            $table->boolean('pet_allowed')->default(false);
            $table->boolean('conversation_allowed')->default(true);
            $table->boolean('music_allowed')->default(true);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
