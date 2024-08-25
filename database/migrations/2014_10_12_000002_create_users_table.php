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

            $table->string('email')->unique()->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->timestamp('is_phone_verified')->nullable();
            $table->string('password')->nullable();



            $table->string('avatar_file_id')->nullable();
            $table->string('national_cart_id')->nullable();
            $table->string('img_1')->nullable();
            $table->string('img_2')->nullable();


            $table->string('status')->nullable();
            $table->string('city')->nullable();
            $table->string('language')->nullable();


            $table->timestamp('is_active')->nullable();
            $table->timestamp('is_blocked')->nullable();

            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_login_ip')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('group_id')
            ->nullable()
            ->constrained('case_groups')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('currency_id')
            ->nullable()
            ->constrained('currencies')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('country_id')
            ->nullable()
            ->constrained('countries')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->timestamps();
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
