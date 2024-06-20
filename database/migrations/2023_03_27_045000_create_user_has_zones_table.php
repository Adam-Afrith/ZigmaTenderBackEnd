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
        Schema::create('user_has_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('zone_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate("NO ACTION");
            $table->foreign('zone_id')->references('id')->on('zone_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_has_zones');
    }
};
