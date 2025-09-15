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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            
            // Template Information (for creating episodes)
            $table->string('title'); // Default title template for episodes
            $table->string('title_english')->nullable(); // Default English title template
            $table->string('title_japanese')->nullable(); // Default Japanese title template
            $table->string('duration')->nullable(); // Default episode duration
            $table->string('slug')->unique();
            $table->string('studio')->nullable();
            
            // Content Information
            $table->longText('description')->nullable(); // General series description
            
            // Series Information
            $table->string('status')->nullable(); // completed, ongoing, upcoming, cancelled
            $table->string('total_episodes');
            
            // Series Media (Optional - episodes have their own)
            $table->string('cover_image')->nullable();
            $table->string('banner_image')->nullable();
            
            // Statistics (Aggregated from episodes)
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('favorite_count')->default(0);
            
            // Publishing
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            
            // SEO & Metadata
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'visibility']);
            $table->index(['is_featured', 'published_at']);
            $table->fullText(['title', 'title_english', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
