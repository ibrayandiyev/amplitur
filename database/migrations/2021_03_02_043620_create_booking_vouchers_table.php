<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->longText('services')->nullable();
            $table->longText('comments')->nullable();
            $table->date('released_at')->nullable();
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
        Schema::dropIfExists('booking_vouchers');
    }
}
