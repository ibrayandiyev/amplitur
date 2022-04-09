<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_provider_id')->constrained('providers')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['system', 'manual'])->default('system');
            $table->tinyInteger('level')->unsigned()->default(1);
            $table->text('message');
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
        Schema::table('client_logs', function (Blueprint $table) {
            $table->dropForeign('provider_logs_target_provider_id_foreign');
            $table->dropForeign('provider_logs_user_id_foreign');
            $table->dropForeign('provider_logs_provider_id_foreign');
        });

        Schema::dropIfExists('provider_logs');
    }
}
