<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionalables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('additionalable_id')->index();
            $table->string('additionalable_type')->index();
            $table->foreignId('additional_id')->constrained()->onDelete('cascade');
            $table->float('price', 8, 5)->default(0.0);
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
        Schema::table('additionalables', function (Blueprint $table) {
            $table->dropForeign('additionalables_additional_id_foreign');
        });

        Schema::dropIfExists('additionalables');
    }
}
