<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongtripRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('longtrip_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('capacity')->unsigned();
            $table->text('extra_additionals')->nullable();
            $table->text('extra_inclusions')->nullable();
            $table->text('extra_exclusions')->nullable();
            $table->text('extra_observations')->nullable();
            $table->json('fields')->nullable();
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
        Schema::Table('longtrip_routes', function (Blueprint $table) {
            $table->dropForeign('longtrip_routes_offer_id_foreign');
        });

        Schema::dropIfExists('longtrip_routes');
    }
}
