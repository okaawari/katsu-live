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
        Schema::create('view_analytics', function (Blueprint $table) {
            $table->id();
            $table->morphs('viewable'); // viewable_type, viewable_id (anime, episode, etc.)
            
            // Date Period
            $table->date('date'); // The date this record represents
            $table->enum('period', ['day', 'week', 'month']); // Aggregation period
            
            // View Counts
            $table->integer('total_views')->default(0);
            $table->integer('unique_views')->default(0); // Unique visitors
            $table->integer('returning_views')->default(0); // Same visitor, multiple views
            
            // Geographic Breakdown (optional, can be null to save space)
            $table->json('country_breakdown')->nullable(); // {'US': 150, 'JP': 75, ...}
            $table->json('device_breakdown')->nullable(); // {'mobile': 200, 'desktop': 100, ...}
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->unique(['viewable_type', 'viewable_id', 'date', 'period']);
            $table->index(['viewable_type', 'viewable_id', 'period', 'date']);
            $table->index(['date', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_analytics');
    }
};
