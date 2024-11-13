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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->required();
            $table->decimal('total_kotor', 10,2)->required();
            $table->integer('diskon')->default(0);
            $table->integer('pajak')->default(0);
            $table->decimal('total_bersih', 10,2)->required();
            $table->enum('status', ['success', 'cancel', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
