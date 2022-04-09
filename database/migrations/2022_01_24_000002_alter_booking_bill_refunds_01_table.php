<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingBillRefunds01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_bill_refunds', function (Blueprint $table) {
            $table->text('history')->nullable()->after('status');
            $table->text('json_object')->nullable()->after('history');
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
            $table->dropColumn(['history', 'json_object']);
        });
    }
}
