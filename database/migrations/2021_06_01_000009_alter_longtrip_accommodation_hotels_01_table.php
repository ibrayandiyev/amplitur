<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLongtripAccommodationHotels01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('longtrip_accommodation_hotels', function (Blueprint $table) {
            $table->foreignId('hotel_id')->nullable()->constrained()->onDelete('cascade');
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
            $table->dropForeign('longtrip_accommodation_hotels_hotel_id_foreign');

            $table->dropColumn([
                'hotel_id']);
        });
    }
}
