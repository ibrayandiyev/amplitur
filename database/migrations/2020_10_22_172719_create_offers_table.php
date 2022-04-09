<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['all', 'bus-trip', 'hotel', 'shuttle', 'ticket', 'travel-insurance', 'food', 'airfare', 'transfer', 'additional']);
            $table->datetime('expires_at')->nullable();
            $table->string('ip')->nullable();
            $table->enum('currency', ['BRL', 'EUR', 'USD', 'GBP']);
            $table->boolean('can_register_additionals')->default(false);
            $table->enum('status', ['active', 'refused', 'suspended', 'in-analysis', 'inactive'])->default('in-analysis');
            $table->string('image')->nullable();
            $table->json('flags')->nullable();
            $table->timestamps();
        });

        Schema::table('bustrip_routes', function (Blueprint $table) {
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
        });

        Schema::table('shuttle_routes', function (Blueprint $table) {
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_provider_id_foreign');
            $table->dropForeign('offers_company_id_foreign');
            $table->dropForeign('offers_package_id_foreign');
        });

        Schema::Table('bustrip_routes', function (Blueprint $table) {
            $table->dropForeign('bustrip_routes_offer_id_foreign');
        });

        Schema::Table('shuttle_routes', function (Blueprint $table) {
            $table->dropForeign('shuttle_routes_offer_id_foreign');
        });
        
        Schema::dropIfExists('offers');
    }
}
