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
        Schema::create('status_notifications', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();
            $table->string('name_fa')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('sms')->nullable();
            $table->boolean('email')->nullable();
            $table->boolean('fcm')->nullable();
            $table->boolean('app')->nullable();


            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
        //
    }
};
