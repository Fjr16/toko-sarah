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
        Schema::create('sellings', function (Blueprint $table) {
            $table->id();
            // $table->integer('selling_id')->required();
            $table->foreignId('user_id')->required();
            $table->foreignId('member_id')->nullable();
            $table->decimal('total_diskon', 10,2)->default(0);
            $table->decimal('total_kotor', 10,2)->default(0)->required();
            $table->decimal('total_pajak', 10,2)->default(0);
            $table->decimal('total_bersih', 10,2)->default(0)->required();
            $table->enum('status', ['paid', 'unpaid', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellings');
    }
};
