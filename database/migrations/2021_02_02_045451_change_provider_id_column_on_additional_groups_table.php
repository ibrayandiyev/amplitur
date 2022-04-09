<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProviderIdColumnOnAdditionalGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_groups', function (Blueprint $table) {
            $table->bigInteger('offer_id')->unsigned();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade')->nullable()->after('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_groups', function (Blueprint $table) {
            $table->bigInteger('provider_id')->nullable(false)->change();
            $table->dropForeign('additional_groups_offer_id_foreign');
            $table->dropColumn('offer_id');
        });
    }
}
