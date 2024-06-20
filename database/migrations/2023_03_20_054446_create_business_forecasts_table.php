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
        Schema::create('business_forecasts', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->integer('call_type_id');
            $table->foreign("call_type_id")->references("id")->on("call_types_mst")->restrictOnDelete()->onUpdate("NO ACTION");
            $table->string('name');
            $table->string('activeStatus');
            $table->integer('created_by');
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
        Schema::dropIfExists('business_forecasts');
    }
};
