<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->string('country')->nullable();
            $table->datetime('starts_at');
            $table->datetime('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('status', ['active', 'refused', 'suspended', 'in-analysis', 'inactive'])->default('in-analysis');
            $table->string('token')->nullable();
            $table->enum('display_type', ['public', 'non-listed', 'out'])->default('non-listed');
            $table->json('flags')->nullable();
            $table->timestamps();
        });

        Schema::table('additionals', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign('packages_provider_id_foreign');
            $table->dropForeign('packages_event_id_foreign');
        });

        Schema::table('additionals', function (Blueprint $table) {
            $table->dropForeign('additionals_package_id_foreign');
            $table->dropColumn('package_id');
        });

        Schema::dropIfExists('packages');
    }
}
