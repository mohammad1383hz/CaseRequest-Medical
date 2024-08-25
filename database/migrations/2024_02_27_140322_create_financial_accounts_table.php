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
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('payment_geteway_id')->nullable();

            $table->enum('account_type',['app','user','wallet','gateway','user_bank'])->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('card_number')->nullable();
            $table->string('ibank')->nullable();
            $table->string('bank')->nullable();
            $table->string('account_number')->nullable();
            $table->foreignId('currency_id')
            ->nullable()
            ->constrained('currencies')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
