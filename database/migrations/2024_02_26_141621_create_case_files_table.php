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
        Schema::create('case_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_request_id')
            ->nullable()
            ->constrained('case_requests')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('case_report_id')
            ->nullable()
            ->constrained('case_reports')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('file_id')
            ->nullable()
            ->constrained('files')
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
        Schema::dropIfExists('case_files');
    }
};

