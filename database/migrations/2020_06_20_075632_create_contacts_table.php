<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contactable_id')->index();
            $table->string('contactable_type')->index();
            $table->string('name')->nullable();
            $table->string('responsible')->nullable();
            $table->string('value')->nullable();
            $table->enum('type', ['residential', 'commercial', 'mobile', 'whatsapp', 'fax', 'financial-email', 'financial-phone', 'booking-email', 'booking-phone', 'other'])->nullable()->default('other');
            $table->boolean('is_primary')->default(false);
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
        Schema::dropIfExists('contacts');
    }
}
