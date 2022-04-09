<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->string('name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('identity')->nullable();
            $table->string('uf')->nullable();
            $table->string('document')->nullable();
            $table->string('passport')->nullable();
            $table->string('registry')->nullable();
            $table->string('address')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_neighborhood')->nullable();
            $table->string('address_complement')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_zip')->nullable();
            $table->string('address_country')->nullable();
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
        Schema::table('booking_clients', function (Blueprint $table) {
            $table->dropForeign('booking_clients_booking_id_foreign');
            $table->dropForeign('booking_clients_client_id_foreign');
        });

        Schema::dropIfExists('booking_clients');
    }
}
