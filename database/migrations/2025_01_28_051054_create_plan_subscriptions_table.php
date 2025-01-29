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
        Schema::create('plan_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('plan_id');
            $table->string('plan_name');
            $table->decimal('amount', 8, 2);
            $table->integer('available_days');
            $table->date('subscribed_date');
            $table->boolean('status')->default(1);
            $table->boolean('is_subscribed')->default(0);
            $table->text('remark')->nullable(); 
            $table->string('talk_time')->nullable(); 
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_subscriptions');
    }
};
