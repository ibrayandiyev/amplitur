<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHotels02Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'refused', 'suspended', 'in-analysis', 'inactive'])->default('in-analysis');
            $table->text('changes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign('hotels_provider_id_foreign');

            $table->dropColumn([
                'changes', 'provider_id', 'status']);
        });
    }
}
