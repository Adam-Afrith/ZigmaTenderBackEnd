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
        Schema::create('bid_management_work_order_mobilization_advances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table -> string('mobadvance')->default(''); 
            $table -> string('bankname')->default(''); 
            $table -> string('bankbranch')->default(''); 
            $table -> string('mobadvmode')->default(''); 
            $table -> date('datemobadv')->nullable(); 
            $table -> date('validupto')->nullable(); 
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
        Schema::dropIfExists('bid_management_work_order_mobilization_advances');
    }
};
