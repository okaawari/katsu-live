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
        Schema::create('anime_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anime_id');
            $table->date('date');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        
            $table->unique(['anime_id', 'date']);
            $table->foreign('anime_id')->references('id')->on('animes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_views');
    }
};
