<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('id')->on('products');
            $table->foreignId('user_id')->constrained('id')->on('users');
            $table->string('subscription_reference');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('next_billing_at')->nullable();
            $table->string('status')->nullable();
            $table->string('gateway')->nullable();
            $table->string('extras')->nullable();
            $table->text('properties')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
