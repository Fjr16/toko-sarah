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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->nullable(false);
            $table->string('batch_number')->nullable(false)->unique();
            $table->date('exp_date')->nullable(false);
            $table->integer('stock')->nullable(false)->default(0);
            $table->decimal('unit_cost', 10,2)->nullable(false)->default(0);
            $table->timestamps();

            // indexes tabel
            $table->index('exp_date');
            $table->index(['item_id', 'exp_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
