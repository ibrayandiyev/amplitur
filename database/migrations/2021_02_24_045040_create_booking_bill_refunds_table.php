<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingBillRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_bill_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('booking_bill_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->float('value', 8, 5)->default(0.0);
            $table->datetime('refunded_at')->nullable();
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
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
        Schema::table('booking_bill_refunds', function (Blueprint $table) {
            $table->dropForeign('booking_bill_refunds_booking_id_foreign');
            $table->dropForeign('booking_bill_refunds_booking_bill_id_foreign');
            $table->dropForeign('booking_bill_refunds_user_id_foreign');
        });

        Schema::dropIfExists('booking_bill_refunds');
    }
}
