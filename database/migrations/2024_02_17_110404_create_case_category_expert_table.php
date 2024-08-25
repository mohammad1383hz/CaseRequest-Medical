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
        Schema::create('case_categories_expert', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_category_id')
            ->nullable()
            ->constrained('case_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('refrence_index')->nullable();
            $table->bigInteger('price')->nullable();
            $table->enum('commission_type',['fixed','percent'])->nullable();
            $table->integer('commission_value')->nullable();
            $table->string('golden_minutes')->nullable();
            $table->boolean('has_penalty')->nullable();
            $table->string('penalty_type')->nullable();
            $table->string('penalty_value')->nullable();
            $table->string('penalty_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_categories_expert');
    }
};
