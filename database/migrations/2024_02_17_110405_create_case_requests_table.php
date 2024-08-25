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
        Schema::create('case_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('case_category_animal_id')
            ->nullable()
            ->constrained('case_category_animals')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('case_category_expert_id')
            ->nullable()
            ->constrained('case_categories_expert')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->string('title')->nullable();
            $table->string('document_no')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('animal_name')->nullable();

            $table->enum('priority',['speed','accurancy'])->nullable();
            $table->enum('status',['submitted','waiting','done','cancelled','refernced','edit_required','draft','block'])->nullable();
            $table->integer('times_refernced')->nullable();
            $table->foreignId('cloned_id')
            ->nullable()
            ->constrained('case_requests')
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
        Schema::dropIfExists('case_requests');
    }
};
