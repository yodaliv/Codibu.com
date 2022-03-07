<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponColumnsToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('plan_id');
            $table->decimal('total_price', 8, 2)->nullable()->after('coupon_code');
            $table->decimal('coupon_discount', 8, 2)->nullable()->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'total_price', 'coupon_discount']);
        });
    }
}
