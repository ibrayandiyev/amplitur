<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongtripAccommodationsPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('longtrip_accommodations_pricings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer_id')->unsigned();
            $table->foreign('offer_id', 'longtrip_accommodations_pricings_offer_id_foreign')->references('id')->on('offers')->onDelete('cascade');
            $table->foreignId('longtrip_route_id')->constrained()->onDelete('cascade');
            $table->bigInteger('longtrip_accommodation_type_id')->unsigned();
            $table->foreign('longtrip_accommodation_type_id', 'longtrip_accommodations_pricings_accommodation_type_id_foreign')->references('id')->on('longtrip_accommodation_types')->onDelete('cascade');
            $table->double('price')->nullable();
            $table->integer('stock')->nullable();
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
        Schema::table('longtrip_accommodations_pricings', function (Blueprint $table) {
            $table->dropForeign('longtrip_accommodations_pricings_offer_id_foreign');
            $table->dropForeign('longtrip_accommodations_pricings_longtrip_route_id_foreign');
            $table->dropForeign('longtrip_accommodations_pricings_accommodation_type_id_foreign');
        });

        Schema::dropIfExists('longtrip_accommodations_pricings');
    }
}
