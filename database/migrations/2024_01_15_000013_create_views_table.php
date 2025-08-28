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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->morphs('viewable'); // viewable_type, viewable_id
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Minimal Visitor Information
            $table->string('visitor_id', 64)->nullable(); // For anonymous users
            $table->ipAddress('ip_address')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            
            // View Metadata
            $table->integer('duration_seconds')->nullable(); // How long they viewed
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['viewable_type', 'viewable_id', 'viewed_at']);
            $table->index(['user_id', 'viewed_at']);
            $table->index(['visitor_id', 'viewed_at']);
            $table->index('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
