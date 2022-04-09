<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInclusionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inclusion_group_id')->nullable()->constrained()->onDelete('set null');
            $table->text('name');
            $table->enum('type', ['bus-trip', 'shuttle', 'hotel', 'hotel-accommodation', 'longtrip']);
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
        Schema::table('inclusions', function (Blueprint $table) {
            $table->dropForeign('inclusions_inclusion_group_id_foreign');
        });

        Schema::dropIfExists('inclusions');
    }
}
