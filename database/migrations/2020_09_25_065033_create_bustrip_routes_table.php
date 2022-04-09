<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShuttleRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shuttle_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('capacity')->unsigned();
            $table->text('extra_additionals')->nullable();
            $table->text('extra_inclusions')->nullable();
            $table->text('extra_exclusions')->nullable();
            $table->text('extra_observations')->nullable();
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

        Schema::dropIfExists('shuttle_routes');
    }
}
