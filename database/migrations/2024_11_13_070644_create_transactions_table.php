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
            $table->string('transaction_code')->required();
            $table->foreignId('supplier_id')->required();
            $table->date('purchase_date')->required();
            $table->integer('subtotal')->required();
            $table->integer('diskon')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('other_cost')->default(0);
            $table->integer('grand_total')->required();
            $table->enum('status', ['pending', 'ordered', 'completed'])->default('pending');
            $table->string('payment_method', 20)->nullable();
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
