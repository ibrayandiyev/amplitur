<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBustripBoardingLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bustrip_boarding_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bustrip_route_id')->constrained()->onDelete('cascade');
            $table->datetime('boarding_at');
            $table->integer('travel_time')->unsigned()->nullable();
            $table->string('country')->nullable();
            $table->float('price', 8, 5)->default(0.0);
            $table->boolean('is_available')->default(true);
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
        Schema::dropIfExists('bustrip_boarding_locations');
    }
}
