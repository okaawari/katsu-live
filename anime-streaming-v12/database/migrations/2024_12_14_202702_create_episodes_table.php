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
        Schema::create('episodes', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('category_id')->nullable()->index('episode_category_id_foreign');
            $table->unsignedInteger('author_id')->index('episode_author_id_foreign');
            $table->string('episode_list')->nullable();
            $table->integer('current_episode')->nullable();
            $table->string('poster', 191);
            $table->string('name', 191);
            $table->string('name_second', 191)->nullable();
            $table->string('name_japanese', 191);
            $table->longText('synopsis');
            $table->integer('status')->default(1);
            $table->string('aired_at', 191);
            $table->string('duration', 191);
            $table->string('stream_1080', 191);
            $table->string('stream_720', 191);
            $table->string('sub_mn');
            $table->string('sub_eng')->nullable();
            $table->string('studio', 191);
            $table->string('translator', 191);
            $table->integer('views')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
