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
            $table->foreignId('user_id')->nullable(false);
            $table->foreignId('customer_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('sale_date')->nullable(false);
            $table->decimal('total_amount', 10,2)->default(0)->nullable(false);
            $table->string('payment_method', 50)->nullable(false);
            $table->decimal('amount_paid', 10,2)->default(0)->nullable(false);
            $table->decimal('change_due', 10,2)->default(0)->nullable(false); //kembalian
            $table->string('sale_status', 50)->nullable(false);
            $table->string('note')->nullable();
            $table->string('additional_cost_name')->nullable(); //untuk custom biaya tambahan
            $table->decimal('additional_cost',10,2)->default(0);
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
