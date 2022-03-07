<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTypeDemoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_type_demo', function (Blueprint $table) {

            $table->unsignedBigInteger('site_type_id');
            $table->foreign('site_type_id')->references('id')->on('site_types')->onDelete('cascade');

            $table->unsignedBigInteger('demo_id');
            $table->foreign('demo_id')->references('id')->on('demos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_demo');
    }
}
