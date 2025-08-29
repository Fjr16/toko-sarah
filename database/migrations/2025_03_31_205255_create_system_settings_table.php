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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 50)->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_email', 100)->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_phone', 20)->nullable();
            $table->string('company_code', 20)->nullable();
            $table->string('currency_symbol', 10)->nullable();
            $table->enum('currency_position_default', ['prefix', 'suffix'])->default('prefix')->required();
            $table->enum('decimal_separator', ['.', ','])->default(',');
            $table->enum('thousand_separator', ['.', ','])->default('.');
            $table->string('notification_email', 50)->nullable();
            $table->string('language', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
