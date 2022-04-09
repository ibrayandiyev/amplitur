<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageColumnToBookingClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_clients', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->default('other')->after('registry');
            $table->enum('language', ['pt-br', 'en', 'es'])->after('phone');
            $table->enum('type', ['fisical', 'legal'])->default('fisical')->after('birthdate');
            $table->enum('primary_document', ['identity', 'passport', 'document'])->nullable()->default('identity')->after('uf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_clients', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('language');
            $table->dropColumn('type');
            $table->dropColumn('primary_document');
        });
    }
}
