<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingOffers01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_offers', function (Blueprint $table) {
            $table->float('price_net', 12, 5)->default(0)->after('price');
            $table->float('sale_coefficient', 7, 2)->default(0)->after('price_net');
            $table->bigInteger('currency_origin_id')->unsigned()->nullable()->after('currency_id');
            $table->bigInteger('company_id')->unsigned()->nullable()->after('currency_origin_id');
            $table->foreign('currency_origin_id')->on('currencies')->references('id');
            $table->foreign('company_id')->on('companies')->references('id');
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
            $table->dropForeign('booking_offers_currency_origin_id_foreign');
            $table->dropForeign('booking_offers_company_id_foreign');
            $table->dropColumn(['price_net', 'sale_coefficient', 'currency_origin_id', 'company_id']);
        });
    }
}
