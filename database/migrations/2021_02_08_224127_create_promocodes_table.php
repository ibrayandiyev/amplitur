<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promocode_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->constrained();
            $table->string('name');
            $table->string('code');
            $table->float('discount_value', 8, 5)->default(0.0);
            $table->integer('stock')->default(0);
            $table->integer('usages')->default(0);
            $table->integer('max_installments')->default(0);
            $table->boolean('cancels_cash_discount')->default(true);
            $table->date('expires_at')->nullable();
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
        Schema::table('promocodes', function (Blueprint $table) {
            $table->dropForeign('promocodes_promocode_group_id_foreign');
            $table->dropForeign('promocodes_promocode_payment_method_id_foreign');
            $table->dropForeign('promocodes_promocode_currency_id_foreign');
        });

        Schema::dropIfExists('promocodes');
    }
}
