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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('address')->nullable();
            $table->string('job_title')->nullable();
            $table->string('status')->nullable();
            $table->string('classification')->nullable();
            $table->string('phone_country_code')->nullable();
            $table->string('number_phone')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->string('token')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('properties')->nullable();
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
        Schema::dropIfExists('users');
    }
};
