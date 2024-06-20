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
        Schema::create('bid_management_work_order_letter_of_acceptences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table -> date('date')->nullable(); 
            $table -> string('refrence_no')->default(''); 
            $table -> string('from')->default(''); 
            $table -> string('medium')->default(''); 
            $table -> string('med_refrence_no')->default(''); 
            $table -> string('medium_select')->default(''); 
            $table -> string('wofile')->default(''); 
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
        Schema::dropIfExists('bid_management_work_order_letter_of_acceptences');
    }
};
