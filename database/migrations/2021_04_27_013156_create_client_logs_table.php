<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_client_id')->constrained('clients')->onDelete('cascade');
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
            $table->dropForeign('client_logs_target_client_id_foreign');
            $table->dropForeign('client_logs_user_id_foreign');
            $table->dropForeign('client_logs_provider_id_foreign');
        });

        Schema::dropIfExists('client_logs');
    }
}
