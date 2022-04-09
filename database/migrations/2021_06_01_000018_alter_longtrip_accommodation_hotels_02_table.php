<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLongtripAccommodationHotels02Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('longtrip_accommodation_hotels', function (Blueprint $table) {
            $table->datetime('checkin')->change();
            $table->datetime('checkout')->change();
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
            $table->datetime('checkin')->change();
            $table->datetime('checkout')->change();
        });
    }
}
