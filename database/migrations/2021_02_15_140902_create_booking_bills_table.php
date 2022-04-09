<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->float('total', 8, 5);
            $table->enum('status', ['pending', 'paid', 'canceled']);
            $table->smallInteger('installment');
            $table->string('url');
            $table->datetime('expires_at');
            $table->datetime('paid_at');
            $table->datetime('canceled_at');
            $table->datetime('viewed_at');
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
        Schema::dropIfExists('booking_bills');
    }
}
