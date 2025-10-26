<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->index()->nullable();
            $table->string('status')->index();
            $table->string('locale')->nullable();
            $table->string('timezone')->nullable();
            $table->text('comment')->nullable();
            $table->json('options')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('tracked_streak')->default(0)->index();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('domain_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
