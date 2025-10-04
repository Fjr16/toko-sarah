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
        Schema::create('purchase_temp_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_temp_id')->nullable(false);
            $table->foreignId('item_id')->nullable(false);
            $table->string('temp_batch_number')->nullable(false)->unique(['item_id','batch_number']);
            $table->date('exp_date')->nullable(false);
            $table->unsignedInteger('qty')->default(0);
            $table->decimal('unit_price',12,2)->default(0);
            $table->decimal('discount',12,2)->default(0);
            $table->decimal('tax',12,2)->default(0);
            $table->decimal('sub_total', 12,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_temp_details');
    }
};
