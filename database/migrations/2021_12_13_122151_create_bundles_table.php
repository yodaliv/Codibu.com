<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->float('price');
            $table->string("cpuCount");
            $table->string("diskSizeInGb");
            $table->string("bundleId");
            $table->string("instanceType");
            $table->string("isActive");
            $table->string("name");
            $table->string("power");
            $table->string("ramSizeInGb");
            $table->string("transferPerMonthInGb");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundles');
    }
}
