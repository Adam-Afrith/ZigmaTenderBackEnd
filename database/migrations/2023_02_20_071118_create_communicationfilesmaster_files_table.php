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
        Schema::create('communicationfilesmaster_files', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('mainid')->unsigned();
            $table->foreign('mainid')->references('id')->on('communicationfilesmasters')->onDelete("cascade")->onUpdate("NO ACTION");


            $table -> string('file_original_name')->nullable()->default('');
            $table -> string('file_new_name')->nullable()->default('');
            $table -> string('file_type')->nullable()->default('');
            $table -> double('file_size')->nullable()->default(0);
            $table -> string('ext')->nullable()->default('');

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
        Schema::dropIfExists('communicationfilesmaster_files');
    }
};
