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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('id')->on('products');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('price')->nullable();
            $table->string('bill_frequency')->nullable();
            $table->string('trial_period')->nullable();
            $table->string('bill_cycle')->nullable();
            $table->boolean('is_recommend')->nullable();
            $table->boolean('is_free')->nullable();
            $table->boolean('is_visible')->nullable();
            $table->string('display_order')->nullable();
            $table->string('extras')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('plans');
    }
};
