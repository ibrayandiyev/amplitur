<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExclusionablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusionables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exclusionable_id')->index();
            $table->string('exclusionable_type')->index();
            $table->foreignId('exclusion_id')->constrained()->onDelete('cascade');
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
        Schema::table('exclusionables', function (Blueprint $table) {
            $table->dropForeign('exclusionables_exclusion_id_foreign');
        });

        Schema::dropIfExists('exclusionables');
    }
}
