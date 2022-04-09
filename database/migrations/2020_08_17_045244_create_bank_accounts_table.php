<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('currency', ['BRL', 'EUR', 'USD', 'GBP']);
            $table->string('bank')->nullable();
            $table->string('agency')->nullable();
            $table->enum('account_type', ['savings', 'current'])->nullable();
            $table->string('account_number')->nullable();
            $table->string('wire')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('sort_code')->nullable();
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
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeign('bank_accounts_provider_id_foreign');
            $table->dropForeign('bank_accounts_company_id_foreign');
        });

        Schema::dropIfExists('bank_accounts');
    }
}
