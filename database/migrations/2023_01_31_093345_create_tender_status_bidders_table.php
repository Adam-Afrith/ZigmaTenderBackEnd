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
        Schema::create('tender_status_bidders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->bigInteger('competitorId')->unsigned();
            $table->foreign('competitorId')->references('id')->on('competitor_profile_creations')->onDelete("Restrict")->onUpdate("NO ACTION");
            $table->enum('acceptedStatus',["approved","rejected"])->default(null);
            $table->string('reason')->nullable()->default(null);//reason for rejected
            $table->integer('created_userid');
            $table->integer('updated_userid')->nullable()->default(null);
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
        Schema::dropIfExists('tender_status_bidders');
    }
};
