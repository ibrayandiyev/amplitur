<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_templates', function (Blueprint $table) {
            $table->id();
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
            $table->dropForeign('payment_method_templates_payment_method_id_foreign');
        });
        
        Schema::dropIfExists('payment_method_templates');
    }
}
