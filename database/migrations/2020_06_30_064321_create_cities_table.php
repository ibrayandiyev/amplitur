<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('country_id')->constrained();
            $table->string('name');
            $table->string('state_code');
            $table->char('country_code', 2);
            $table->string('tax_code')->nullable();
            $table->decimal('latitude');
            $table->string('longitude');
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId')->nullable();
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
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign('cities_state_id_foreign');
            $table->dropForeign('cities_country_id_foreign');
        });

        Schema::dropIfExists('cities');
    }
}
