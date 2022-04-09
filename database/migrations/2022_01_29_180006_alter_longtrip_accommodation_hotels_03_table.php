<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLongtripAccommodationHotels03Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('longtrip_accommodation_hotels', function (Blueprint $table) {
            $table->integer('longtrip_hotel_label_id')->unsigned()->nullable();
            $table->foreign('longtrip_hotel_label_id')->on('longtrip_hotel_labels')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('longtrip_accommodation_hotels', function (Blueprint $table) {
            $table->dropForeign('longtrip_accommodation_hotels_longtrip_hotel_label_id_foreign');
            $table->dropColumn(['longtrip_hotel_label_id']);
        });
    }
}
