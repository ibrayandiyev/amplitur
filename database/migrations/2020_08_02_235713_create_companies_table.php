<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('set null');
            $table->string('company_name');
            $table->string('legal_name')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('registry')->nullable();
            $table->string('country')->nullable();
            $table->enum('status', ['in-analysis', 'active', 'suspended'])->default('in-analysis');
            $table->enum('language', ['pt-br', 'en', 'es'])->default('en');
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
        Schema::dropIfExists('companies');
    }
}
