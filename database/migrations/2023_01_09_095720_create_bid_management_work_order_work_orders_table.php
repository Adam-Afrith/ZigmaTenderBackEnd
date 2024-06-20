<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_management_work_order_work_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->string('orderquantity')->default('');
            $table->string('priceperunit')->default('');
            $table->date('loadate')->nullable() ;
            $table->date('orderdate')->nullable();
            $table->date('agreedate')->nullable();
            $table->date('sitehandoverdate')->nullable();
            $table->string('wofile')->default('');
            $table->string('agfile')->default('');
            $table->string('shofile')->default('');
            $table -> integer('createdby_userid');
            $table -> integer('updatedby_userid')->nullable()->default(null);
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
        Schema::dropIfExists('bid_management_work_order_work_orders');
    }
};
