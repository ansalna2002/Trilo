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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('user_id')->unique();
            $table->string('name')->nullable();
            $table->string('role')->default('user')->nullable();
            $table->string('otp')->nullable();
            $table->boolean('otp_verified')->default(0);
            $table->string('otp_expires_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'transgender']);
            $table->text('interest')->nullable();
            $table->string('country')->nullable();
            $table->text('language')->nullable();
            $table->text('about')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_subscriber')->default(0);
            $table->timestamp('subscribed_date')->nullable();
            $table->timestamp('subscribed_end_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
