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
        Schema::table('state_masters', function (Blueprint $table) {
            $table->integer('zone_id')->nullable()->after('country_id');
            $table->foreign('zone_id')->references('id')->on('zone_masters')->restrictOnDelete()->onUpdate("NO ACTION");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('state_masters', function (Blueprint $table) {
            //
        });
    }
};
