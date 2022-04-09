<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExclusionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exclusion_group_id')->nullable()->constrained()->onDelete('set null');
            $table->text('name');
            $table->enum('type', ['bus-trip', 'shuttle', 'hotel', 'longtrip']);
            $table->boolean('is_exclusive')->default(false);
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
        Schema::table('exclusions', function (Blueprint $table) {
            $table->dropForeign('exclusions_exclusion_group_id_foreign');
        });

        Schema::dropIfExists('exclusions');
    }
}
