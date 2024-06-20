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
        Schema::create('zone_has_states', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('zone_id')->default(105);
            $table->foreign('zone_id')->references('id')->on('zone_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('state_masters')->restrictOnDelete()->onUpdate("NO ACTION");
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
        Schema::dropIfExists('zone_has_states');
    }
};
