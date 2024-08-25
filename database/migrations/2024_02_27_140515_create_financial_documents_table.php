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
        Schema::create('financial_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creditor_id')
            ->nullable()
            ->constrained('financial_accounts')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('debtor_id')
            ->nullable()
            ->constrained('financial_accounts')
            ->cascadeOnUpdate()
            ->nullOnDelete();


            $table->string('description')->nullable();
            $table->string('tracking_code')->nullable();
            $table->timestamp('date')->nullable();
            $table->bigInteger('price')->nullable();
            $table->boolean('is_canceled')->nullable();
            $table->foreignId('invoice_id')
            ->nullable()
            ->constrained('invoices')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('file_id')
            ->nullable()
            ->constrained('files')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('withdraw_request_id')
            ->nullable()
            ->constrained('withdraw_requests')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('currency_id')
            ->nullable()
            ->constrained('currencies')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('status')->nullable();
            $table->bigInteger('case_request_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_documents');
    }
};
