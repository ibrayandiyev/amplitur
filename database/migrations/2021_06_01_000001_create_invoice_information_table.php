<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null');
            $table->json('description')->nullable();
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
        Schema::table('invoice_information', function (Blueprint $table) {
            $table->dropForeign('invoice_information_currency_id_foreign');
        });
        Schema::dropIfExists('invoice_information');
    }
}
