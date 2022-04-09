<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagePaymentMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_payment_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
            $table->string('processor')->nullable()->default(null);
            $table->float('tax', 8, 5)->nullable()->default(0.0);
            $table->float('discount', 8, 5)->nullable()->default(0.0);
            $table->integer('limiter')->nullable()->default(0);
            $table->integer('max_installments')->nullable()->default(12);
            $table->boolean('first_installment_billet')->default(true);
            $table->boolean('is_active')->default(true);
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
        Schema::table('package_payment_method', function (Blueprint $table) {
            $table->dropForeign('package_payment_method_package_id_foreign');
            $table->dropForeign('package_payment_method_payment_method_id_foreign');
        });

        Schema::dropIfExists('package_payment_method');
    }
}
