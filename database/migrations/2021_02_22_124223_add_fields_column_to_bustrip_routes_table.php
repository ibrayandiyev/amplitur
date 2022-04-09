<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsColumnToBustripRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bustrip_routes', function (Blueprint $table) {
            $table->json('fields')->nullable()->after('extra_observations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bustrip_routes', function (Blueprint $table) {
            $table->dropColumn('fields');
        });
    }
}
