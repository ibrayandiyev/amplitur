<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdditionalGroupIdToAdditionalGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_groups', function (Blueprint $table) {
            $table->bigInteger('additional_group_id')->unsigned()->nullable();
            $table->foreign('additional_group_id')->references('id')->on('additional_groups')->onDelete('set null');
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
            $table->dropForeign('additional_groups_additional_group_id_foreign');
            $table->dropColumn('additional_group_id');
        });
    }
}
