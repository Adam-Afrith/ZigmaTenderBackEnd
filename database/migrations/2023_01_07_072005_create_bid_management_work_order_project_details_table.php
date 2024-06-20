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
        Schema::create('bid_management_work_order_project_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table -> string('properiod')->default(''); 
            $table -> string('mobPeriod')->default(''); 
            $table -> string('monsoonperiod')->default(''); 
            $table -> string('monthduration')->default(''); 
            $table -> string('supplyscape')->default(''); 
            $table -> date('supplydate')->nullable(); 
            $table -> date('erectionstart')->nullable(); 
            $table -> date('commercialproduc')->nullable(); 
            $table -> date('tarcompletion')->nullable(); 
            $table -> date('produccompletion')->nullable(); 
            $table -> integer('createdby_userid');
            $table -> integer('updatedby_userid')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_management_work_order_project_details');
    }
};
