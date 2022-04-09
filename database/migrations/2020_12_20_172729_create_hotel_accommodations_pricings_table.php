<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelAccommodationsPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_accommodations_pricings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer_id')->unsigned();
            $table->foreign('offer_id', 'accommodations_pricings_offer_id_foreign')->references('id')->on('offers')->onDelete('cascade');
            $table->bigInteger('hotel_accommodation_id')->unsigned();
            $table->foreign('hotel_accommodation_id', 'accommodations_pricings_accommodation_id_foreign')->references('id')->on('hotel_accommodations')->onDelete('cascade');
            $table->double('price')->nullable();
            $table->integer('stock')->nullable();
            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();
            $table->boolean('required_overnight')->default(false);
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
        Schema::table('hotel_accommodations_pricings', function (Blueprint $table) {
            $table->dropForeign('accommodations_pricings_offer_id_foreign');
            $table->dropForeign('accommodations_pricings_accommodation_id_foreign');
        });

        Schema::dropIfExists('hotel_accommodations_pricings');
    }
}
