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
        Schema::create('episode_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('episode_id')->constrained()->onDelete('cascade');
            
            // List Status (now for individual episodes)
            $table->enum('status', [
                'plan_to_watch',
                'watching', 
                'completed', 
                'on_hold', 
                'dropped'
            ])->default('plan_to_watch');
            
            // Watch Tracking (simplified for individual episodes)
            $table->integer('watch_count')->default(0); // How many times watched
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_watched_at')->nullable();
            
            // User Rating & Review
            $table->decimal('user_rating', 3, 2)->nullable(); // 1.00 to 10.00
            $table->text('review')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_favorite')->default(false);
            
            // Privacy
            $table->boolean('is_private')->default(false);
            
            // Custom fields
            $table->json('custom_tags')->nullable(); // User's custom tags
            $table->integer('priority')->default(0); // Watch priority
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['user_id', 'episode_id']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'is_favorite']);
            $table->index(['episode_id', 'user_rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_lists');
    }
};
