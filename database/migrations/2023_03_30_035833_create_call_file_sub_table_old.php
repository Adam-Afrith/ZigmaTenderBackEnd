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
        Schema::create('call_file_sub', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('mainid')->unsigned();
            $table->foreign('mainid')->references('id')->on('call_log_creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->string('filename');
            $table->string('originalfilename');
            $table->string('filetype')->default('');
            $table->string('filesize')->default('');
            $table->string('hasfilename')->default('');
            $table->integer('createdby_userid');
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
        Schema::dropIfExists('call_file_subs');
    }
};
