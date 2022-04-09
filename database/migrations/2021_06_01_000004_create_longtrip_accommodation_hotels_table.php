<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongtripAccommodationHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('longtrip_accommodation_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('longtrip_accommodation_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('complement')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamp('checkin')->nullable();
            $table->timestamp('checkout')->nullable();
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
        Schema::table('longtrip_accommodation_hotels', function (Blueprint $table) {
            $table->dropForeign('longtrip_accommodation_hotels_longtrip_accommodation_id_foreign');
        });

        Schema::dropIfExists('longtrip_accommodation_hotels');
    }
}
