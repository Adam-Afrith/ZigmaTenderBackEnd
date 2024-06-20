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
        Schema::create('competitor_profile_creations', function (Blueprint $table) {
            $table->id();
            $table->string('compNo');
            $table->string('compName');
            $table->string('registrationType');
            $table->year('registerationYear');
            $table->bigInteger('country')->unsigned();
            $table->foreign('country')->references('id')->on('country_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('state')->unsigned();
            $table->foreign('state')->references('id')->on('state_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('district')->unsigned();
            $table->foreign('district')->references('id')->on('district_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('city')->unsigned();
            $table->foreign('city')->references('id')->on('city_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->text('address');
            $table->integer('pincode');
            $table->string('panNo',11);
            $table->bigInteger('mobile');
            $table->string('email');
            $table->string('gstNo',15);
            $table->string('directors');
            $table->string('companyType');
            $table->integer('manpower');
            $table->string('cr_userid');
            $table->string('edited_userid')->nullable()->default(null);
            $table->integer('delete_status')->default('0'); 
            $table->timestamps();

            // $table->foreign('customer_sub_category')->references('id')->on('customer_sub_categories')->restrictOnDelete()->onUpdate("NO ACTION");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitor_profile_creations');
    }
};
