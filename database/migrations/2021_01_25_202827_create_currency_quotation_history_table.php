<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyQuotationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_quotation_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('origin_currency_id')->unsigned();
            $table->foreign('origin_currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->bigInteger('target_currency_id')->unsigned();
            $table->foreign('target_currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->float('quotation', 8, 5)->default(1.0);
            $table->float('spread', 8, 5)->default(1.0);
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
        Schema::table('currency_quotation_history', function (Blueprint $table) {
            $table->dropForeign('currency_quotation_history_origin_currency_id');
            $table->dropForeign('currency_quotation_history_target_currency_id');
        });

        Schema::dropIfExists('currency_quotation_history');
    }
}
