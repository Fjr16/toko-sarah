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
            $table->decimal('base_price',10,2)->required()->default(0);
            $table->integer('stok')->default(0);
            // $table->enum('status', ['active', 'deleted'])->default('active');
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
