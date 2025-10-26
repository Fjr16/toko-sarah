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
            $table->foreignId('user_id')->nullable(false);
            $table->foreignId('supplier_id')->nullable(false);
            $table->string('invoice_number')->nullable(false);
            $table->date('purchase_date')->nullable(false);
            $table->decimal('subtotal',12,2)->default(0);
            $table->decimal('diskon',12,2)->default(0);
            $table->decimal('tax',12,2)->default(0);
            $table->decimal('other_cost',12,2)->default(0);
            $table->decimal('grand_total',12,2)->default(0);
            $table->string('purchase_status')->nullable(false);
            $table->string('notes')->nullable();
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
