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
        Schema::create('case_category_animals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_category_id')
            ->nullable()
            ->constrained('case_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('price')->nullable();
            $table->enum('commission_type',['fixed','percent'])->nullable();
            $table->bigInteger('commission_value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_categories_expert_commission');
    }
};
