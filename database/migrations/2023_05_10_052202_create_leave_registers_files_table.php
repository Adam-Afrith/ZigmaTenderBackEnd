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
        Schema::create('leave_registers_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("mainid")->unsigned();
            $table->foreign ('mainid')->references('id')->on('leave_registers')->OnDelete("CASCADE")->onUpdate("NO ACTION");
            $table->string('filename')->nullable();
            $table->string('filetype')->nullable();
            $table->string('filesize',10);
            $table->string('hasfilename')->nullable();
            $table->integer('created_by');
            $table->integer('edited_by')->nullable();
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
        Schema::dropIfExists('leave_registers_files');
    }
};
