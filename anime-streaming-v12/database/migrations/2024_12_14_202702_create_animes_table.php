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
            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable()->index('animes_category_id_foreign');
            $table->unsignedInteger('author_id')->index('animes_author_id_foreign');
            $table->string('name', 191);
            $table->string('stream_480')->nullable();
            $table->string('stream_720', 191)->nullable();
            $table->string('stream_1080')->nullable();
            $table->string('sub_mn', 191)->nullable();
            $table->string('sub_eng', 100)->nullable();
            $table->string('episode_list')->nullable();
            $table->integer('current_episode')->nullable();
            $table->string('name_second', 191)->nullable();
            $table->string('name_japanese', 191);
            $table->longText('synopsis')->nullable();
            $table->integer('status')->default(1);
            $table->string('aired_at', 191);
            $table->string('duration', 191);
            $table->string('studio', 191);
            $table->string('translator', 191);
            $table->string('review')->nullable();
            $table->integer('views')->nullable();
            $table->string('poster', 191);
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
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
