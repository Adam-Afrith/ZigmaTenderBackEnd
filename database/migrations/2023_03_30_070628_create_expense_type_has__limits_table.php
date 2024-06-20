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
        Schema::create('expense_type_has__limits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("expnseType_id")->unsigned();
            $table->foreign ('expnseType_id')->references('id')->on('expense_types')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->bigInteger("userType_id")->unsigned();
            $table->foreign ('userType_id')->references('id')->on('roles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer('isUnlimited')->default('1');
            $table->double('limit')->nullable()->default(null);
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
        Schema::dropIfExists('expense_type_has__limits');
    }
};
