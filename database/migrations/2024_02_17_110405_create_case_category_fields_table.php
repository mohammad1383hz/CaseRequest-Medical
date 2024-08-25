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
        Schema::create('case_category_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_category_animal_id')
            ->nullable()
            ->constrained('case_category_animals')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->string('name')->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('index')->nullable();
            $table->longText('options')->nullable();

            $table->enum('type',['input','select','texstarea','checkbox','file'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_category_fields');
    }
};
