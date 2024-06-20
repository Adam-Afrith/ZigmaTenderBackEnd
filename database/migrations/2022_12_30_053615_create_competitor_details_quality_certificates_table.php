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
        Schema::create('competitor_details_quality_certificates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("compId")->unsigned();
            $table->foreign("compId")->references("id")->on("competitor_profile_creations")->onDelete("cascade")->onUpdate("NO ACTION");
            $table->string('compNo');
            $table->string('cerName');
            $table->string('filepath');
            $table->string('filetype');
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('competitor_details_quality_certificates');
    }
};
