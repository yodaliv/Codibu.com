<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 150);
            $table->string('domain', 150)->unique();
            $table->enum('domain_type', ['purchase_request', 'already_owned']);
            $table->string('server_ip')->nullable();
            $table->string('old_server_ip')->nullable();
            $table->string('admin_password')->nullable();
            $table->string('db_pass');
            $table->enum('status', ['queued', 'building', 'failed', 'completed', 'deleted', 'running','stopping','stopped']);
            $table->enum('theme_type', ['plain', 'demo']);

            $table->string('stack_id', 150)->nullable();

            $table->unsignedBigInteger('theme_id')->default(1);
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
            $table->unsignedBigInteger('demo_id')->default(1);
            $table->foreign('demo_id')->references('id')->on('demos')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
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
        Schema::dropIfExists('sites');
    }
}
