<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLongtripAccommodations01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('longtrip_accommodations', function (Blueprint $table) {
            $table->dropColumn(['name', 'address', 'number', 'neighborhood', 'complement', 'city', 'state',
                'zip', 'country', 'latitude', 'longitude', 'checkin', 'checkout', 'images']);
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
        });

    }
}
