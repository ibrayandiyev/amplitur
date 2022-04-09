<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHotelAccommodations02Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_accommodations', function (Blueprint $table) {
            $table->dropForeign('hotel_accommodations_hotel_id_foreign');
            $table->foreign('hotel_offers_id')->on('hotel_offers')->references('id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_accommodations', function (Blueprint $table) {
            $table->dropForeign('hotel_accommodations_hotel_offers_id_foreign');
            $table->foreign('hotel_offers_id', 'hotel_accommodations_hotel_id_foreign')->on('hotels')->references('id')->constrained()->onDelete('cascade');
        });
    }
}
