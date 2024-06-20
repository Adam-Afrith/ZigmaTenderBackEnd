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
        //
        Schema::create('calltobdm_has_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("calltobdm_id")->unsigned();
            $table->foreign ('calltobdm_id')->references('id')->on('calltobdms')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign ('customer_id')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
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
        //
    }
};
