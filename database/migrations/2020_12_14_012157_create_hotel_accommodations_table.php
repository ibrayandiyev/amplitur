<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_accommodation_type_id')->constrained()->onDelete('cascade');
            $table->json('images')->nullable();
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
        Schema::table('hotel_accommodations', function (Blueprint $table) {
            $table->dropForeign('hotel_accommodations_hotel_id_foreign');
            $table->dropForeign('hotel_accommodations_hotel_accommodation_type_id_foreign');
        });

        Schema::dropIfExists('hotel_accommodations');
    }
}
