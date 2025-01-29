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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id'); // Make sure it's string
            $table->string('receiver_id'); // Make sure it's string
            $table->text('message');
            $table->boolean('is_read')->default(0);
            $table->boolean('is_deleted')->default(0)->nullable();
            $table->text('image')->nullable();
            $table->string('voice_record')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
