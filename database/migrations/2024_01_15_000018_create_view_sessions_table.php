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
        Schema::create('view_sessions', function (Blueprint $table) {
            $table->id();
            $table->morphs('viewable'); // viewable_type, viewable_id
            
            // Session Information (minimal)
            $table->string('session_id', 64); // Unique session identifier
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->ipAddress('ip_address')->nullable();
            
            // Basic Metadata
            $table->string('country', 2)->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            
            // Session Duration
            $table->integer('duration_seconds')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('last_activity_at')->useCurrent();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->unique(['viewable_type', 'viewable_id', 'session_id']);
            $table->index(['viewable_type', 'viewable_id', 'started_at']);
            $table->index(['session_id']);
            $table->index(['user_id', 'started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_sessions');
    }
};
