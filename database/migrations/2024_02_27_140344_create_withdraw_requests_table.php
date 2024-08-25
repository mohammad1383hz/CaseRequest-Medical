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
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('financial_account_id')
            ->nullable()
            ->constrained('financial_accounts')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->string('status')->nullable();
            $table->string('price')->nullable();

            $table->string('description')->nullable();
            $table->foreignId('file_id')
            ->nullable()
            ->constrained('files')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->timestamp('date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};
