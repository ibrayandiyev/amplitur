<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColumnToBookingPassengerAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_passenger_additionals', function (Blueprint $table) {
            $table->float('price', 8, 5)->default(0)->after('additional_id');
            $table->foreignId('currency_id')->after('additional_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_passenger_additionals', function (Blueprint $table) {
            $table->dropForeign('booking_passenger_additionals_currency_id_foreign');
            $table->dropColumn('currency_id');
            $table->dropColumn('price');
        });
    }
}
