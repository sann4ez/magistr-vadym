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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->index();
            $table->string('key')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->longText('desc')->nullable();
            $table->string('color')->nullable();
            $table->string('bg')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('weight')->default(0);
            $table->boolean('is_guarded')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
