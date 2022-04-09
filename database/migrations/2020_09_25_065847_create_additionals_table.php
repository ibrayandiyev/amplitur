<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('additional_group_id')->nullable()->constrained()->onDelete('set null');
            $table->text('name');
            $table->enum('currency', ['BRL', 'EUR', 'USD', 'GBP']);
            $table->float('price', 8, 5)->default(0.0);
            $table->integer('stock')->default(0);
            $table->json('type')->nullable(true);
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
        Schema::table('additionals', function (Blueprint $table) {
            $table->dropForeign('additionals_provider_id_foreign');
            $table->dropForeign('additionals_additional_group_id_foreign');
        });

        Schema::dropIfExists('additionals');
    }
}
