<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
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
        Schema::table('promocode_groups', function (Blueprint $table) {
            $table->dropForeign('promocode_groups_package_id_foreign');
        });

        Schema::dropIfExists('promocode_groups');
    }
}
