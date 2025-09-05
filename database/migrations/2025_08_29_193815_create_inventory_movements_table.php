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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullable(false);
            $table->foreignId('item_id')->constrained()->nullable(false);
            $table->foreignId('product_batch_id')->constrained()->nullable(false);
            $table->string('flag',20)->nullable(false);
            $table->integer('qty')->nullable(false)->default(0);
            $table->string('unit', 50)->nullable(false); //diambil dari satuan terkecil produk;
            $table->nullableMorphs('reference');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
