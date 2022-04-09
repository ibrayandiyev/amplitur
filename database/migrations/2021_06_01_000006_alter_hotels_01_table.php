<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHotels01Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->dropForeign('hotels_offer_id_foreign');
            $table->dropColumn(['offer_id']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('cascade');

            $table->dropColumn([
                'description', 'deleted_at']);
        });
    }
}
