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
        Schema::create('leave_registers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->unsigned();
            $table->foreign ('user_id')->references('id')->on('users')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->tinyInteger("attendance_type_id")->unsigned();
            $table->foreign ('attendance_type_id')->references('id')->on('attendance_types')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->date('from_date')->format('d/m/Y');
            $table->date('to_date')->format('d/m/Y');
            $table->time('start_time', 0)->format('H:i:s');
            $table->string('reason')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('created_by');
            $table->integer('edited_by')->nullable();
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
        Schema::dropIfExists('leave_registers');
    }
};
