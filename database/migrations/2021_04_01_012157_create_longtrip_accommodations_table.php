<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongtripAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('longtrip_accommodations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer_id')->unsigned()->onDelete('cascade');
            $table->bigInteger('longtrip_route_id')->unsigned()->onDelete('cascade');
            $table->foreignId('longtrip_accommodation_type_id')->constrained()->onDelete('cascade');
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
        Schema::table('longtrip_accommodations', function (Blueprint $table) {
            $table->dropForeign('longtrip_accommodations_offer_id_foreign');
            $table->dropForeign('longtrip_accommodations_longtrip_route_id_foreign');
            $table->dropForeign('longtrip_accommodations_longtrip_accommodation_type_id_foreign');
        });

        Schema::dropIfExists('longtrip_accommodations');
    }
}
