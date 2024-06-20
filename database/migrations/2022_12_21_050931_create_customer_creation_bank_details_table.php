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
        Schema::create('customer_creation_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('ifsccode');
            $table->string('bankname')->default('');
            $table->string('bankaddress')->default('');
            $table->string('beneficiaryaccountname')->default('');
            $table->string('accountnumber')->default('');
            $table->bigInteger('cust_creation_mainid')->unsigned();
            $table->foreign('cust_creation_mainid')->references('id')->on('customer_creation_profiles')->onDelete('cascade')->onUpdate("NO ACTION");
            $table->integer('createdby_userid');
            $table->integer('updatedby_userid')->nullable();
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
        Schema::dropIfExists('customer_creation_bank_details');
    }
};
