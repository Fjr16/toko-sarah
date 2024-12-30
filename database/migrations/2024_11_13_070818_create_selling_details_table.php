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
            $table->foreignId('selling_id')->required();
            $table->foreignId('item_id')->required();
            $table->string('product_barcode')->required(); //barcode pabrik
            $table->string('product_name')->required(); //nama produk
            $table->string('product_satuan')->required(); //satuan produk
            $table->integer('product_jumlah')->required(); //jumlah produk per satuan
            $table->decimal('product_harga', 10,2)->default(0)->required(); //harga satuan produk
            $table->decimal('product_sub_total', 10,2)->default(0)->required(); //sub total harga satuan produk
            $table->decimal('product_diskon', 10,2)->default(0); //diskon per produk
            // $table->decimal('product_pajak', 10,2)->default(0); //pajak per produk
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
