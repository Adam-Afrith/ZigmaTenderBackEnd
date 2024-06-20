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
        Schema::create('call_log_creations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign ('customer_id')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->date('call_date')->format('Y/m/d h:i:s A');
            $table->integer("call_type_id");
            $table->foreign('call_type_id')->references('id')->on('call_types_mst')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer("bizz_forecast_id");
            $table->foreign('bizz_forecast_id')->references('id')->on('business_forecasts')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer("bizz_forecast_status_id");
            $table->foreign('bizz_forecast_status_id')->references('id')->on('business_forecast_statuses')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger("executive_id")->unsigned(); //staff id
            $table->foreign ('executive_id')->references('id')->on('users')->restrictOnDelete()->onUpdate("NO ACTION");

            $table->integer("procurement_type_id");
            $table->foreign('bizz_forecast_id')->references('id')->on('call_procurement_types')->restrictOnDelete()->onUpdate("NO ACTION");

            $table->enum('action', ['next_followup', 'close'])->default('next_followup');
            $table->date('next_followup_date')->format('Y/m/d')->nullable();
            $table->date('close_date')->format('Y/m/d')->nullable();
            $table->integer('close_status_id')->nullable();
            $table->foreign('close_status_id')->references('id')->on('call_close_statuses')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->text("additional_info")->nullable();
            $table->text("remarks")->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            //have to create sub table for file maintainenece multiple file uploads for every call log entry
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_log_creations');
    }
};
