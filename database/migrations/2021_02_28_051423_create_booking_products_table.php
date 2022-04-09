<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id');
            $table->foreignId('currency_id');
            $table->string('product_type');
            $table->string('product_id');
            $table->date('date');
            $table->float('price', 8, 5);
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
        Schema::table('booking_products', function (Blueprint $table) {
            $table->dropForeign('booking_products_booking_id_foreign');
            $table->dropForeign('booking_products_currency_id_foreign');
        });

        Schema::dropIfExists('booking_products');
    }
}
