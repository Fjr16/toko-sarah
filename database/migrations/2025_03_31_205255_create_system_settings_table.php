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
            $table->enum('currency_position_default', ['prefix', 'suffix'])->default('prefix')->required();
            $table->string('company_name', 50)->required();
            $table->string('company_email', 50)->required();
            $table->string('company_phone', 20)->required();
            $table->string('company_address')->required();
            $table->string('notification_email', 50)->required();
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
