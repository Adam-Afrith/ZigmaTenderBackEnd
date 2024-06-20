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
        Schema::table('users', function (Blueprint $table) {
            $table->string('userName'); //to store Staff name for display purpose
            $table->BigInteger('userType')->unsigned();
            $table->foreign('userType')->references('id')->on('roles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('mobile')->unique();
            $table->string('photo');
            $table->string('confirm_passsword');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('filesize');
            $table->string('fileext');
            $table->integer('createdby');
            $table->integer('updatedby')->nullable(); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->dropColumn('phone');
            $table->dropColumn('photo');
            $table->dropColumn('createdby');
            $table->dropColumn('updatedby');

        });
    }
};
