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
            $table->string('code')->unique();
            $table->decimal('percent_off', 5, 2);
            $table->enum('duration', ['once', 'repeating', 'forever'])->default('once');
            $table->dateTime('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('stripe_coupon_id');
            $table->string('stripe_promotion_code_id');
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
