<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsColumnToAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additionals', function (Blueprint $table) {
            $table->json('fields')->nullable();
            $table->string('availability')->default('public');
            $table->json('allowed_providers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additionals', function (Blueprint $table) {
            $table->dropColumn('fields');
            $table->dropColumn('availability');
            $table->dropColumn('allowed_providers');
        });
    }
}
