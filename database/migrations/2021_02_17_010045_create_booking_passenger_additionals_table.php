<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPassengerAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_passenger_additionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('booking_passenger_id')->constrained();
            $table->foreignId('additional_id')->constrained();
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
        Schema::table('booking_passenger_addtionals', function (Blueprint $table) {
            $table->dropForeign(('booking_passenger_additionals_booking_id_foreign'));
            $table->dropForeign(('booking_passenger_additionals_booking_passenger_id_foreign'));
            $table->dropForeign(('booking_passenger_additionals_additional_id_foreign'));
        });

        Schema::dropIfExists('booking_passenger_additionals');
    }
}
