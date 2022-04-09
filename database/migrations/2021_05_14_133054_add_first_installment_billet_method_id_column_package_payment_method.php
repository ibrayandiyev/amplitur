<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirstInstallmentBilletMethodIdColumnPackagePaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_payment_method', function (Blueprint $table) {
            $table->bigInteger('first_installment_billet_method_id')->nullable()->after('first_installment_billet');
            $table->string('first_installment_billet_processor')->nullable()->after('first_installment_billet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_payment_method', function (Blueprint $table) {
            $table->dropColumn('first_installment_billet_method_id');
            $table->dropColumn('first_installment_billet_processor');
        });
    }
}
