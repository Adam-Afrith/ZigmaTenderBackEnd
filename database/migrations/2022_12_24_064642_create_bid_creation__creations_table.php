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
        Schema::create('bid_creation__creations', function (Blueprint $table) {
            $table->id();
            $table -> string('bidno')->default(''); 
            $table -> string('customername')->default(''); 
            $table -> string('bidcall')->default(''); 
            $table -> string('tenderid')->default(''); 
            $table -> string('tenderinvtauth')->default(''); 
            $table -> string('tenderref')->default(''); 
            $table->bigInteger('state')->unsigned();
            $table->foreign('state')->references('id')->on('state_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table -> bigInteger('ulb')->unsigned(); 
            $table->foreign('ulb')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table -> string('TenderDescription')->default(''); 
            $table -> date('NITdate')->nullable(); 
            $table -> date('submissiondate')->nullable(); 
            // $table -> string('quality')->default(''); 
            $table->double('quality')->default(null);
            $table -> string('unit'); 
            $table -> string('tenderevalutionsysytem'); 
            // $table -> date('projectperioddate1')->nullable(); 
            // $table -> date('projectperioddate2')->nullable(); 
            $table->double('projectperioddate1');
            $table->string('projectperioddate2');
            $table -> double('estprojectvalue');
            $table -> double('tenderfeevalue');
            $table -> double('priceperunit');
            $table -> string('emdmode'); 
            $table -> double('emdamt'); 
            $table -> string('dumpsiter')->default(''); 
            $table -> date('prebiddate')->nullable(); 
            $table -> string('EMD')->default(''); 
            $table -> string('location')->default('');
            $table -> bigInteger("tendercreation")->unsigned();
            $table -> foreign("tendercreation")->references("id")->on("tender_creations")->onDelete("cascade")->onUpdate("NO ACTION");
            $table -> integer('createdby_userid');
            $table -> integer('updatedby_userid')->nullable(); 
            $table -> timestamps();
        });
    }
   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_creation__creations');
    }
};
