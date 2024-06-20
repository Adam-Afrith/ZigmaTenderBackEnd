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
        Schema::create('tender_status_financial_evaluations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");


            $table->bigInteger('techsubId')->unsigned();
            $table->foreign('techsubId')->references('id')->on('tender_status_tech_evaluations_subs')->onDelete("cascade")->onUpdate("NO ACTION");

            $table->bigInteger('competitorId')->unsigned();
            $table->foreign('competitorId')->references('id')->on('competitor_profile_creations')->onDelete("Restrict")->onUpdate("NO ACTION");

            $table->double('amt')->nullable();

            $table->bigInteger('unit')->unsigned()->nullable();
            $table->foreign('unit')->references('id')->on('unit_masters')->onDelete("Restrict")->onUpdate("NO ACTION");

            $table->string('least')->nullable();

            $table->integer('created_by');
            $table->integer('edited_by')->nullable()->default(null);
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
        Schema::dropIfExists('tender_status_financial_evaluations');
    }
};
