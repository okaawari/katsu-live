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
        Schema::dropIfExists('video_watch_progress');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the table if needed to rollback
        Schema::create('video_watch_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('episode_id')->constrained()->onDelete('cascade');
            
            // Progress Information
            $table->decimal('current_time', 10, 2)->default(0.00);
            $table->decimal('duration', 10, 2)->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            
            // Watch Status
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_skipped')->default(false);
            $table->integer('watch_count')->default(1);
            
            // Quality & Playback Info
            $table->string('quality_watched')->nullable();
            $table->string('subtitle_language')->nullable();
            $table->decimal('playback_speed', 3, 2)->default(1.00);
            
            // Device & Session Info
            $table->string('device_type')->nullable();
            $table->string('platform')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_position_update')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->unique(['user_id', 'episode_id']);
            $table->index(['user_id', 'is_completed']);
            $table->index(['episode_id', 'progress_percentage']);
            $table->index('last_position_update');
        });
    }
};
