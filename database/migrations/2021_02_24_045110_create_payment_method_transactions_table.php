<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained();
            $table->foreignId('booking_id')->nullable()->constrained();
            $table->foreignId('booking_bill_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->float('value', 8, 5)->default(0.0);
            $table->integer('installment')->nullable();
            $table->string('country');
            $table->string('gateway');
            $table->string('gateway_response_code')->nullable();
            $table->string('gateway_response_message')->nullable();
            $table->longText('gateway_payload')->nullable();
            $table->string('order')->nullable();
            $table->string('authorization')->nullable();
            $table->string('nsu')->nullable();
            $table->string('authentication')->nullable();
            $table->string('sqn')->nullable();
            $table->string('rid')->nullable();
            $table->string('holder')->nullable();
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
        Schema::table('payment_method_transactions', function (Blueprint $table) {
            $table->dropForeign('payment_method_transactions_payment_method_id_foreign');
            $table->dropForeign('payment_method_transactions_booking_id_foreign');
            $table->dropForeign('payment_method_transactions_booking_bill_id_foreign');
            $table->dropForeign('payment_method_transactions_user_id_foreign');
        });
        Schema::dropIfExists('payment_method_transactions');
    }
}
