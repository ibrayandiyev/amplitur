<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToHotelAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_accommodations', function (Blueprint $table) {
            $table->text('extra_inclusions')->nullable();
            $table->text('extra_exclusions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_accommodations', function (Blueprint $table) {
            $table->dropColumn('extra_inclusions');
            $table->dropColumn('extra_exclusions');
        });
    }
}
