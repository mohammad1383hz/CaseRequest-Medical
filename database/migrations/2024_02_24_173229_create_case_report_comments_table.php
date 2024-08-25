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
        Schema::create('case_report_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
            ->nullable()
            ->constrained('case_report_comments')
            ->cascadeOnUpdate()
            ->nullOnDelete();

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

            $table->string('message')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_report_comments');
    }
};
