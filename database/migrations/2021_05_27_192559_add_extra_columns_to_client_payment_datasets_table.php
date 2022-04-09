<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToClientPaymentDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_payment_datasets', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->dropColumn('token');
            $table->json('payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_payment_datasets', function (Blueprint $table) {
            $table->dropForeign('client_payment_datasets_booking_id_foreign');
            $table->dropColumn('booking_id');
            $table->string('token')->nullable();
            $table->dropColumn('payload');
        });
    }
}
