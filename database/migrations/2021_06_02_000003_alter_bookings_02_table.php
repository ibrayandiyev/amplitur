<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookings02Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('check_contract')->nullable()->after('ip');
            $table->decimal('discount_promocode', 8,2)->nullable()->after('discount');
            $table->decimal('discount_promocode_provider', 8,2)->nullable()->after('discount_promocode');
            $table->decimal('tax', 8,2)->nullable()->after('discount_promocode_provider');
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
            $table->dropColumn(['check_contract', 'discount_promocode', 'discount_promocode_provider', 'tax']);
        });
    }
}
