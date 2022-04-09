<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('email')->unique();
            $table->date('birthdate')->nullable();
            $table->string('identity')->nullable();
            $table->string('uf')->nullable();
            $table->string('document')->nullable();
            $table->string('passport')->nullable();
            $table->string('registry')->nullable()->unique();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->default('other');
            $table->enum('language', ['pt-br', 'en', 'es'])->default('en');
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_valid')->default(false);
            $table->boolean('is_newsletter_subscriber')->default(false);
            $table->enum('type', ['fisical', 'legal'])->default('fisical');
            $table->enum('primary_document', ['identity', 'passport', 'document'])->nullable()->default('identity');
            $table->string('country')->nullable();
            $table->string('responsible_name')->nullable();
            $table->string('responsible_email')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('clients');
    }
}
