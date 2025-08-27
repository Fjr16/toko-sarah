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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')->constrained()->nullable(false);
            $table->string('code')->nullable(false);    //barcode pabrik
            $table->string('name')->nullable(false);
            $table->string('small_unit')->nullable(false);
            $table->string('medium_unit')->nullable();
            $table->string('big_unit')->nullable();
            $table->integer('medium_to_small')->nullable();
            $table->integer('big_to_medium')->nullable();
            $table->decimal('default_cost',10,2)->nullable(false)->default(0);   //10,2 ==> 10 digit total, 8 digit sebelum koma dan 2 digit dibelakang koma 10000000.00
            $table->integer('margin')->nullable(false)->default(0);
            $table->decimal('default_price',10,2)->nullable(false)->default(0);
            $table->integer('all_stok')->default(0);
            $table->integer('stok_alert')->default(0);
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->string('status', 20)->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
