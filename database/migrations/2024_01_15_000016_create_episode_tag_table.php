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
        Schema::create('episode_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            
            // Metadata
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable(); // Optional notes about why this tag was added
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->unique(['episode_id', 'tag_id']);
            $table->index(['episode_id']);
            $table->index(['tag_id']);
            $table->index(['added_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_tag');
    }
};
