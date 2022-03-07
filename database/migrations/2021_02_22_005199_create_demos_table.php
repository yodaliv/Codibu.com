<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("url");
            $table->text("description")->nullable();
            $table->text("tags")->nullable();
            $table->integer("blog_prefix");

            $table->unsignedBigInteger('network_id');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');

            $table->unsignedBigInteger('site_types_id');
            $table->foreign('site_types_id')->references('id')->on('site_types')->onDelete('cascade');

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
        Schema::dropIfExists('demos');
    }
}
