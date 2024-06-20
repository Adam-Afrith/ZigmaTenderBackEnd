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
        Schema::create('attendance_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned();
            $table->tinyInteger('attendanceType')->unsigned();
            $table->foreign('attendanceType')->references('id')->on('attendance_types')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer('created_by');
            $table->integer('edited_by')->nullable()->default(null);
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
        Schema::dropIfExists('attendance_entries');
    }
};
