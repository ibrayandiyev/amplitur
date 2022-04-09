<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained();
            $table->foreignId('offer_id')->constrained();
            $table->bigInteger('product_id');
            $table->string('product_type');
            $table->foreignId('client_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->tinyInteger('passengers')->default(1);
            $table->enum('status', ['suspended', 'canceled', 'confirmed', 'pending', 'in-analysis'])->default('pending');
            $table->enum('payment_status', ['pending', 'confirmed', 'overdued', 'on-going', 'refunded', 'pending_confirmation', 'blocked'])->default('pending');
            $table->enum('document_status', ['pending', 'confirmed', 'on-going', 'partial_received'])->default('pending');
            $table->enum('voucher_status', ['released', 'pending'])->default('pending');
            $table->float('subtotal', 8, 5);
            $table->float('discount', 8, 5)->nullable();
            $table->float('total', 8, 5);
            $table->smallInteger('installments')->nullable();
            $table->json('quotations')->nullable();
            $table->string('ip')->nullable();
            $table->string('comments')->nullable();
            $table->datetime('starts_at')->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->datetime('canceled_at')->nullable();
            $table->datetime('expired_at')->nullable();
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_package_id_foreign');
            $table->dropForeign('bookings_offer_id_foreign');
            $table->dropForeign('bookings_client_id_foreign');
            $table->dropForeign('bookings_currency_id_foreign');
        });

        Schema::dropIfExists('bookings');
    }
}
