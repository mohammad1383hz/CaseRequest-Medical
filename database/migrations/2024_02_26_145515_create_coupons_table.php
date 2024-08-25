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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->timestamp('end_date')->nullable();
            $table->foreignId('case_category_id')
            ->nullable()
            ->constrained('case_categories')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('case_group_id')
            ->nullable()
            ->constrained('case_groups')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->integer('count')->nullable();
            $table->integer('use_count')->nullable();
            $table->boolean('filter_user');

            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();
            $table->bigInteger('discount')->nullable();
            $table->enum('type',['static','percent'])->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
