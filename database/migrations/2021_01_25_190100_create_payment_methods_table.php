<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['national', 'international'])->default('national');
            $table->enum('type', ['credit', 'debit', 'billet', 'check', 'cash', 'invoice'])->default('credit');
            $table->string('name');
            $table->string('code');
            $table->tinyInteger('max_installments')->default(12);
            $table->boolean('first_installment_billet')->default(0);
            $table->boolean('offline')->default(0);
            $table->boolean('komerci')->default(0);
            $table->boolean('rede')->default(0);
            $table->boolean('cielo')->default(0);
            $table->boolean('shopline')->default(0);
            $table->boolean('paypal')->default(0);
            $table->boolean('bradesco')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
