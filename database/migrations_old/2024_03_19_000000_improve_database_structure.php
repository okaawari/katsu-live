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
        // Improve users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->timestamp('sub_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
        });

        // Improve animes table
        Schema::table('animes', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['name', 'name_japanese']);
            $table->index('status');
            $table->index('posted_at');
            $table->softDeletes();
        });

        // Improve episodes table
        Schema::table('episodes', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['name', 'name_japanese']);
            $table->index('status');
            $table->softDeletes();
        });

        // Improve comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('comment_id', 'id');
            $table->foreign('reply_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('anime_id')->references('id')->on('animes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('created_at');
            $table->softDeletes();
        });

        // Improve favorites table
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('anime_id')->references('id')->on('animes')->onDelete('cascade');
            $table->unique(['user_id', 'anime_id']);
        });

        // Improve animelists table
        Schema::table('animelists', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('anime_id')->references('id')->on('animes')->onDelete('cascade');
            $table->unique(['user_id', 'anime_id', 'type_id']);
        });

        // Improve payment_histories table
        Schema::table('payment_histories', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('amount');
            $table->string('status')->default('completed')->after('payment_method');
            $table->json('metadata')->nullable()->after('status');
        });

        // Improve roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->boolean('is_system')->default(false)->after('description');
        });

        // Improve permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('module')->nullable()->after('description');
        });

        // Improve tags table
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('description')->nullable()->after('name_mn');
        });

        // Improve categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('description')->nullable()->after('name');
            $table->string('icon')->nullable()->after('description');
            $table->integer('order')->default(0)->after('icon');
        });

        // Improve slider table
        Schema::table('slider', function (Blueprint $table) {
            $table->rename('sliders');
            $table->foreign('anime_id')->references('id')->on('animes')->onDelete('set null');
            $table->string('title')->nullable()->after('anime_id');
            $table->string('description')->nullable()->after('title');
            $table->integer('order')->default(0)->after('published');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'avatar', 'sub_date', 'expire_date', 'is_active']);
            $table->dropSoftDeletes();
        });

        // Revert animes table
        Schema::table('animes', function (Blueprint $table) {
            $table->dropForeign(['category_id', 'author_id']);
            $table->dropIndex(['name', 'name_japanese']);
            $table->dropIndex(['status']);
            $table->dropIndex(['posted_at']);
            $table->dropSoftDeletes();
        });

        // Revert episodes table
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropForeign(['category_id', 'author_id']);
            $table->dropIndex(['name', 'name_japanese']);
            $table->dropIndex(['status']);
            $table->dropSoftDeletes();
        });

        // Revert comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('id', 'comment_id');
            $table->dropForeign(['reply_id', 'anime_id', 'user_id']);
            $table->dropIndex(['created_at']);
            $table->dropSoftDeletes();
        });

        // Revert favorites table
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'anime_id']);
            $table->dropUnique(['user_id', 'anime_id']);
        });

        // Revert animelists table
        Schema::table('animelists', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'anime_id']);
            $table->dropUnique(['user_id', 'anime_id', 'type_id']);
        });

        // Revert payment_histories table
        Schema::table('payment_histories', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'status', 'metadata']);
        });

        // Revert roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['slug', 'is_system']);
        });

        // Revert permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['slug', 'module']);
        });

        // Revert tags table
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description']);
        });

        // Revert categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'icon', 'order']);
        });

        // Revert slider table
        Schema::table('sliders', function (Blueprint $table) {
            $table->rename('slider');
            $table->dropForeign(['anime_id']);
            $table->dropColumn(['title', 'description', 'order']);
            $table->dropSoftDeletes();
        });
    }
}; 