<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyAndQuotesColumnToBookingBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_bills', function (Blueprint $table) {
            $table->foreignId('currency_id')->after('payment_method_id')->constrained();
            $table->json('quotations')->after('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_bills', function (Blueprint $table) {
            $table->dropForeign('bookings_bills_currency_id_foreign');
            $table->dropColumn('currency_id');
            $table->dropColumn('quotations');
        });
    }
}
