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
        Schema::create('business_forecast_statuses', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string("status_name")->unique();
            $table->integer("bizz_forecast_id")->unique();
            $table->foreign('bizz_forecast_id')->references('id')->on('business_forecasts')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->enum('active_status', ['active', 'inactive'])->default('active');
            $table->integer('created_userid');
            $table->integer('edited_userid')->nullable()->default(null);
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
        Schema::dropIfExists('business_forecast_statuses');
    }
};
