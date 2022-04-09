<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('offer_id')->constrained();
            $table->float('price', 8, 5)->default(0);
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
        Schema::table('booking_offers', function (Blueprint $table) {
            $table->dropForeign('booking_offers_booking_id_foreign');
            $table->dropForeign('booking_offers_offer_id_foreign');
        });

        Schema::dropIfExists('booking_offers');
    }
}
