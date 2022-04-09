<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->enum('status', ['active', 'suspended', 'banned', 'pending'])->default('pending');
            $table->enum('type', ['master', 'admin', 'manager'])->default('manager');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
