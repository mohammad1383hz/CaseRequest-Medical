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
        Schema::create('case_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('case_assignment_id')
            ->nullable()
            ->constrained('case_assignments')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->double('case_score')->nullable();
            $table->double('report_score')->nullable();
            $table->timestamp('time_response')->nullable();
            $table->double('time_response_score')->nullable();


            $table->string('tech')->nullable();
            $table->string('interpretation')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_reports');
    }
};
