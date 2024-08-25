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
        Schema::create('case_request_fields', function (Blueprint $table) {
            $table->foreignId('case_request_id')
            ->nullable()
            ->constrained('case_requests')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('case_category_field_id')
            ->nullable()
            ->constrained('case_category_fields')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_request_fields');
    }
};
