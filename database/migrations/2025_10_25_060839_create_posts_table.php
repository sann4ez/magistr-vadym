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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();
            $table->text('teaser')->nullable();
            $table->longText('body')->nullable();
            $table->json('locales')->nullable();
            $table->integer('sort')->default(0);
            $table->integer('duration')->default(0);
            $table->boolean('is_free')->index();
            $table->string('status')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('user_id')->nullable()->index();
            $table->uuid('category_id')->nullable()->index();
            $table->uuid('sound_id')->nullable()->index();
            $table->uuid('domain_id')->nullable()->index();

            $table->json('index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
