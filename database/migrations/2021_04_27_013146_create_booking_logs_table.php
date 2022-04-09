<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('target_booking_id')->constrained('bookings')->onDelete('cascade');
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
        Schema::table('booking_logs', function (Blueprint $table) {
            $table->dropForeign('booking_logs_target_client_id_foreign');
            $table->dropForeign('booking_logs_target_booking_id_foreign');
            $table->dropForeign('booking_logs_user_id_foreign');
            $table->dropForeign('booking_logs_provider_id_foreign');
        });

        Schema::dropIfExists('booking_logs');
    }
}
