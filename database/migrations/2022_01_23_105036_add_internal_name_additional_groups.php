<?php

use App\Models\AdditionalGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInternalNameAdditionalGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table ('additional_groups', function (Blueprint $table){

            $table->string('internal_name')->nullable()->after('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::table ('additional_groups', function (Blueprint $table){

            $table->dropColumn('internal_name');
        });
    }
}
