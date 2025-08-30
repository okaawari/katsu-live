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
        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('episode_id')->constrained()->onDelete('cascade');
            $table->decimal('current_time', 10, 2)->default(0.00); // Current position in seconds
            $table->decimal('duration', 10, 2)->nullable(); // Total video duration in seconds
            $table->timestamps();
            
            // Unique constraint to ensure one progress record per user per episode
            $table->unique(['user_id', 'episode_id']);
            $table->index(['user_id', 'current_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_progress');
    }
};