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
        Schema::create('customer_creation_contact_people', function (Blueprint $table) {
            $table->id();
            $table->string('contact_person')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('designation');
            $table->bigInteger('cust_creation_mainid')->unsigned();
            $table->foreign('cust_creation_mainid')->references('id')->on('customer_creation_profiles')->onDelete('cascade')->onUpdate("NO ACTION");
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
        Schema::dropIfExists('customer_creation_contact_people');
    }
};
