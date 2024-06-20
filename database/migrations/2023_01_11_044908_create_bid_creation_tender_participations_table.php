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
        Schema::create('bid_creation_tender_participations', function (Blueprint $table) {
            $table->id();
            $table -> string('tenderparticipation');
            $table -> bigInteger("bidCreationMainId")->unsigned();
            $table -> foreign("bidCreationMainId")->references("id")->on("bid_creation__creations")->onDelete("cascade")->onUpdate("NO ACTION");
            $table -> integer('createdby_userid');
            $table -> integer('updatedby_userid')->nullable(); 
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
        Schema::dropIfExists('bid_creation_tender_participations');
    }
};
