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
        Schema::create('anime_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('anime_id')->index('anime_tag_anime_id_foreign');
            $table->unsignedInteger('tag_id')->index('anime_tag_tag_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_tag');
    }
};
