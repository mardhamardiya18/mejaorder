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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('phone');
            $table->string('external_id')->nullable();
            $table->string('checkout_url')->nullable();

            $table->string('payment_type')->nullable();
            $table->string('status')->default(null);
            $table->integer('subtotal');
            $table->integer('ppn');
            $table->integer('total');

            $table->foreignId('barcode_id')
                ->constrained('barcodes')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};