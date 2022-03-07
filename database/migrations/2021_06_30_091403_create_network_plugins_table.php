<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkPluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_plugins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('network_id');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
            $table->unsignedBigInteger('plugin_id');
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');
            $table->string('version')->nullable();
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
        Schema::dropIfExists('network_plugins');
    }
}
