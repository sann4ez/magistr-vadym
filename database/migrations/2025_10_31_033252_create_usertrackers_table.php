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
        Schema::create('usertrackers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('period');
            $table->decimal('value')->default(0);
            $table->json('data')->nullable();
            $table->date('fixed_at')->nullable();
            $table->timestamps();

            $table->uuid('user_id')->constrained()->onDelete('cascade');
        });

        Schema::create('usertracker_emotion', function (Blueprint $table) {
            $table->uuid('usertracker_id')->index();
            $table->uuid('emotion_id')->index();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('usertracker_id')->references('id')->on('usertrackers')->onDelete('cascade');
            $table->foreign('emotion_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usertracker_emotion');

        Schema::dropIfExists('usertrackers');
    }
};
