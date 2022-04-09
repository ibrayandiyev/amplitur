<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInclusionablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inclusionables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inclusionable_id')->index();
            $table->string('inclusionable_type')->index();
            $table->foreignId('inclusion_id')->constrained()->onDelete('cascade');
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
        Schema::table('inclusionables', function (Blueprint $table) {
            $table->dropForeign('inclusionables_inclusion_id_foreign');
        });

        Schema::dropIfExists('inclusionables');
    }
}
