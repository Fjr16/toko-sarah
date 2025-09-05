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
        Schema::create('selling_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selling_id')->nullable(false);
            $table->foreignId('item_id')->nullable(false);
            $table->foreignId('product_batch_id')->nullable(false);
            $table->integer('qty')->nullable(false);
            $table->string('unit')->nullable(false);
            $table->decimal('price', 10,2)->default(0)->nullable(false);
            $table->decimal('sub_total', 10,2)->default(0)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_details');
    }
};
