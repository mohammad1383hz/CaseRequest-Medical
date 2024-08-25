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
        Schema::create('case_report_surveries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('case_report_id')
            ->nullable()
            ->constrained('case_reports')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('case_survery_field_id')
            ->nullable()
            ->constrained('case_report_survery_fields')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_report_surveries');
    }
};
