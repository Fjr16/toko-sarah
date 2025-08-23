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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable(false);
            $table->string('address')->nullable();
            $table->string('subdistrict', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal_code', 20)->nullable(false);
            $table->string('nik', 20)->nullable(false);
            $table->string('member_code')->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
