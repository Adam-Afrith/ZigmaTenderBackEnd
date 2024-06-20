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
        Schema::create('tender_status_tech_evaluations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bidid')->unsigned();//bidderId - id of 'tender_status_bidders' table
            $table->foreign('bidid')->references('bidid')->on('tender_status_bidders')->onDelete("cascade")->onUpdate("NO ACTION");
            // $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->date('evaluationDate');
            $table->string('document');
            $table->integer('created_userid');
            $table->integer('edited_userid')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_status_tech_evaluations');
    }
};
