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
        Schema::create('bdm_has_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("bdm_id")->unsigned();
            $table->foreign ('bdm_id')->references('id')->on('users')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign ('customer_id')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->enum('assign_status',['0','1']);
            $table->integer('created_userid');
            $table->integer('edited_userid')->nullable()->default(null);
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
        Schema::table('bdm_has_customers', function (Blueprint $table) {
            //
        });
    }
};
