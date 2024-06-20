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
        Schema::create('district_masters', function (Blueprint $table) {
            $table->id();
            $table->string('district_name');
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('state_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->foreign('country_id')->references('id')->on('country_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->string('district_status');
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
        Schema::dropIfExists('district_masters');
    }
};
