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
        Schema::create('u_l_b_details', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();
            $table->string('population2011')->nullable();
            // $table->string('presentpopulation')->nullable();
            $table->string('wards')->nullable();
            $table->string('households')->nullable();
            $table->string('commercial')->nullable();
            $table->string('ABbusstand')->nullable();
            $table->string('CDbusstand')->nullable();
            $table->string('market_morethan_oneacre')->nullable();
            $table->string('market_lessthan_oneacre')->nullable();
            $table->string('lengthofroad')->nullable();
            $table->string('lengthofrouteroad')->nullable();
            $table->string('lengthofotherroad')->nullable();
            $table->string('lengthoflanes')->nullable();
            $table->string('lengthofpucca')->nullable();
            $table->string('lengthofcutcha')->nullable();
            $table->string('parks')->nullable();
            $table->string('parksforpublicuse')->nullable();
            $table->string('tricycle')->nullable();
            $table->string('bov')->nullable();
            $table->string('bovrepair')->nullable();
            $table->string('lcv')->nullable();
            $table->string('lcvrepair')->nullable();
            $table->string('compactor')->nullable();
            $table->string('hookloaderwithcapacity')->nullable();
            $table->string('compactorbin')->nullable();
            $table->string('hookloader')->nullable();
            $table->string('tractortipper')->nullable();
            $table->string('lorries')->nullable();
            $table->string('jcb')->nullable();
            $table->string('bobcat')->nullable();
            $table->string('sanitaryworkers_sanctioned')->nullable();
            $table->string('sanitaryworkers_inservice')->nullable();
            $table->string('sanitarysupervisor_sanctioned')->nullable();
            $table->string('sanitarysupervisor_inservice')->nullable();
            $table->string('permanentdrivers')->nullable();
            $table->string('regulardrivers')->nullable();
            $table->string('publicgathering')->nullable();
            $table->string('secondarystorage')->nullable();
            $table->string('transferstation')->nullable();
            $table->string('households_animatorsurvey')->nullable();
            $table->string('assessments_residential')->nullable();
            $table->string('assessments_commercial')->nullable();
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
        Schema::dropIfExists('u_l_b_details');
    }
};
