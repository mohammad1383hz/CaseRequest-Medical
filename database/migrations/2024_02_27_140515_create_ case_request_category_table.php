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
        Schema::create('case_request_category', function (Blueprint $table) {

            $table->foreignId('case_request_id')
            ->nullable()
            ->constrained('case_requests')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('case_category_id')
            ->nullable()
            ->constrained('case_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();

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
