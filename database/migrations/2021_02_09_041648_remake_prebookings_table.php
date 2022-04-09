<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemakePrebookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('prebookings');

        Schema::create('prebookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->integer('passengers')->nullable();
            $table->enum('responsible', ['client', 'agency'])->default('client');
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
        Schema::table('prebookings', function (Blueprint $table) {
            $table->dropForeign('prebookings_event_id_foreign');
            $table->dropForeign('prebookings_client_id_foreign');
        });

        Schema::drop('prebookings');
    }
}
