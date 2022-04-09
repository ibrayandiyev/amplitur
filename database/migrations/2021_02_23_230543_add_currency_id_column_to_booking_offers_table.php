<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyIdColumnToBookingOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_offers', function (Blueprint $table) {
            $table->foreignId('currency_id')->after('offer_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_offers', function (Blueprint $table) {
            $table->dropForeign('bookings_bills_currency_id_foreign');
            $table->dropColumn('currency_id');
        });
    }
}
