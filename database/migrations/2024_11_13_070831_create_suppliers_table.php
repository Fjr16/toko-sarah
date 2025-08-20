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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->string('company_name', 100)->nullable(); //jika berbeda dengan nama supplier
            $table->string('type', 50)->nullable(false); //enum SupplierType
            $table->string('contact_person', 50)->nullable(); //enum SupplierType
            $table->string('phone', 20)->nullable(false);
            $table->string('email', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 20)->nullable();
            $table->string('province', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('tax_number', 50)->nullable();//npwp atau identitas pajak
            $table->string('bank_account', 50)->nullable();
            $table->string('bank_number', 50)->nullable();
            $table->string('status', 50)->nullable(); //enum Status
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
