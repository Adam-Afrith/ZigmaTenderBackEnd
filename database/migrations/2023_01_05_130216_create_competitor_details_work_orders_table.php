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
        Schema::create('competitor_details_work_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("compId")->unsigned();
            $table->foreign("compId")->references("id")->on("competitor_profile_creations")->onDelete("cascade")->onUpdate("NO ACTION");
            $table->string('compNo');
            $table->string('custName');
            $table->string('projectName');
            $table->string('tnederId');
            $table->bigInteger('state')->unsigned();
            $table->foreign("state")->references("id")->on("state_masters")->restrictOnDelete()->onUpdate("NO ACTION");
            $table->date('woDate');
            $table->string('quantity');
            $table->bigInteger('unit')->unsigned();
            $table->foreign('unit')->references('id')->on('unit_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->string('projectValue');
            $table->string('perTonRate');
            $table->string('qualityCompleted');
            $table->date('date');
            $table->string('woFile');
            $table->string('woFileType');
            $table->string('completionFile');
            $table->string('completionFileType');
            $table->integer('cr_userid');
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
        Schema::dropIfExists('competitor_details_work_orders');
    }
};
