<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHotelAccommodations01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_accommodations', function (Blueprint $table) {
            $table->renameColumn('hotel_id', 'hotel_offers_id');
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
            $table->renameColumn('hotel_offers_id', 'hotel_id');
        });
    }
}
