<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongtripBoardingLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('longtrip_boarding_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('longtrip_route_id')->constrained()->onDelete('cascade');
            $table->datetime('boarding_at');
            $table->datetime('ends_at');
            $table->string('country')->nullable();
            $table->float('price', 11, 3)->default(0.0);
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
        Schema::dropIfExists('longtrip_boarding_locations');
    }
}
