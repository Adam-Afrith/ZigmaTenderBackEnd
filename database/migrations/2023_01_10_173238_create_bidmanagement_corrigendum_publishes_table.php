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
        Schema::create('bidmanagement_corrigendum_publishes', function (Blueprint $table) {
            $table->id();
            $table -> date('date');
            $table -> string('file_original_name');
            $table -> string('file_new_name');
            $table -> string('file_type');
            $table -> double('file_size');
            $table -> string('ext');
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
        Schema::dropIfExists('bidmanagement_corrigendum_publishes');
    }
};
