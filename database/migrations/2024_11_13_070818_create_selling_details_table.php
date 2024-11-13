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
            $table->string('code')->required(); //barcode pabrik
            $table->integer('jumlah')->required(); //barcode pabrik
            $table->decimal('diskon', 10,2)->default(0); //barcode pabrik
            $table->decimal('pajak', 10,2)->default(0); //barcode pabrik
            $table->decimal('total_harga', 10,2)->default(0)->required(); //barcode pabrik
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
