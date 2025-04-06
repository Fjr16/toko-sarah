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
            $table->foreignId('item_category_id')->required();
            $table->string('code')->required();    //barcode pabrik
            $table->string('name')->required();
            $table->string('small_unit')->required();
            $table->string('medium_unit')->nullable();
            $table->integer('medium_to_small')->nullable();
            $table->string('big_unit')->nullable();
            $table->integer('big_to_medium')->nullable();
            $table->decimal('cost',10,2)->required()->default(0);   //10,2 ==> 10 digit total, 8 digit sebelum koma dan 2 digit dibelakang koma 10000000.00
            $table->decimal('price',10,2)->required()->default(0);
            $table->integer('stok')->default(0);
            $table->integer('stok_alert')->default(0);
            $table->integer('tax')->nullable();
            $table->enum('tax_type', ['exclusive', 'inclusive', 'none'])->default('none');
            $table->text('note')->nullable();
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
