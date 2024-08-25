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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('currency_id')
            ->nullable()
            ->constrained('currencies')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->enum('status',['created','registered','canceled'])->nullable();
            $table->timestamp('date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_payed')->nullable();
            $table->foreignId('file_id')
            ->nullable()
            ->constrained('files')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->bigInteger('total_items')->nullable();

            $table->bigInteger('total_discount_items')->nullable();
            $table->bigInteger('total_discount')->nullable();

            $table->bigInteger('invoice_payable')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_number_authority')->nullable();


            

            $table->string('qr_code_link')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
