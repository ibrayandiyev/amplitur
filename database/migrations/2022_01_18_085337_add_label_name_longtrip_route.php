<?php

use App\Models\LongtripRoute;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelNameLongtripRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table ('longtrip_routes', function (Blueprint $table){

            $table->string('label_name')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::table ('longtrip_routes', function (Blueprint $table){

            $table->dropColumn('label_name');
        });
    }
}
