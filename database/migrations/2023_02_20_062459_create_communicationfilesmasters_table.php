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
        Schema::create('communicationfilesmasters', function (Blueprint $table) {
            $table->id();
    
            $table -> date('date')->nullable();
            $table -> string('refrence_no')->nullable()->default('');
            $table -> string('fromselect')->nullable()->default('');
            $table -> string('from')->nullable()->default('');

            $table->bigInteger('from_ulb')->unsigned()->nullable();
            $table->foreign('from_ulb')->references('id')->on('customer_creation_profiles')->onDelete("Restrict")->onUpdate("NO ACTION");

            $table -> string('toselect')->nullable()->default('');
            $table -> string('to')->nullable()->default('');
            $table->bigInteger('to_ulb')->unsigned()->nullable();
            $table->foreign('to_ulb')->references('id')->on('customer_creation_profiles')->onDelete("Restrict")->onUpdate("NO ACTION");

            $table -> string('subject')->nullable()->default('');
            $table -> string('medium')->nullable()->default('');
            $table -> string('med_refrence_no')->nullable()->default('');
          
            
            $table->integer('createdby_userid');
            $table->integer('updatedby_userid')->nullable()->default(null);
            
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
        Schema::dropIfExists('communicationfilesmasters');
    }
};
