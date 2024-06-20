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
        Schema::create('customer_creation_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('customer_no');
            $table->string('customer_category');
            $table->string('customer_name');
            $table->string('smart_city');
            $table->bigInteger('customer_sub_category')->unsigned();
            $table->foreign('customer_sub_category')->references('id')->on('customer_sub_categories')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('country')->unsigned();
            $table->foreign('country')->references('id')->on('country_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('state')->unsigned();
            $table->foreign('state')->references('id')->on('state_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('district')->unsigned();
            $table->foreign('district')->references('id')->on('district_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('city')->unsigned();
            $table->foreign('city')->references('id')->on('city_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer('pincode');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('pan')->nullable();
            $table->string('mobile_no')->nullable();
            // $table->date('current_year_date');
            $table->string('email')->nullable();
            $table->string('gst_registered');
            $table->string('gst_no')->nullable();
            // $table->string('population_year_data')->nullable();
            $table->string('website')->nullable();
            $table->integer('createdby_userid');
            $table->integer('updatedby_userid')->nullable();
            $table->integer('delete_status');
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
        Schema::dropIfExists('customer_creation_profiles');
    }
};
