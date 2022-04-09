<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelAccommodationsStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_accommodations_structure', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hotel_accommodation_id')->unsigned();
            $table->foreign('hotel_accommodation_id', 'accommodation_structure_accommodation_id_foreign')->references('id')->on('hotel_accommodations')->onDelete('cascade');
            $table->bigInteger('hotel_accommodation_structure_id')->unsigned();
            $table->foreign('hotel_accommodation_structure_id', 'accommodation_structure_structure_id_foreign')->references('id')->on('hotel_accommodation_structures')->onDelete('cascade');
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
        Schema::table('hotel_accommodations_structure', function (Blueprint $table) {
            $table->dropForeign('accommodation_structure_accommodation_id_foreign');
            $table->dropForeign('accommodation_structure_structure_id_foreign');
        });

        Schema::dropIfExists('hotel_accommodations_structure');
    }
}
