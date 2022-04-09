<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observationables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('observationable_id')->index();
            $table->string('observationable_type')->index();
            $table->foreignId('observation_id')->constrained()->onDelete('cascade');
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
        Schema::table('observationables', function (Blueprint $table) {
            $table->dropForeign('observationables_observation_id_foreign');
        });

        Schema::dropIfExists('observationables');
    }
}
