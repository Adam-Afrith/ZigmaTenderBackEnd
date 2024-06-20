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
        Schema::create('competitor_details_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("compId")->unsigned();
            $table->foreign("compId")->references("id")->on("competitor_profile_creations")->onDelete("cascade")->onUpdate("NO ACTION");
            $table->string('compNo');
            $table->bigInteger("country")->unsigned();
            $table->foreign("country")->references("id")->on("country_masters")->onDelete("Restrict")->onUpdate("NO ACTION");
            $table->bigInteger("state")->unsigned();
            $table->foreign("state")->references("id")->on("state_masters")->onDelete("Restrict")->onUpdate("NO ACTION");
            $table->bigInteger("district")->unsigned();
            $table->foreign("district")->references("id")->on("district_masters")->onDelete("Restrict")->onUpdate("NO ACTION");
            $table->bigInteger("city")->unsigned();
            $table->foreign("city")->references("id")->on("city_masters")->onDelete("Restrict")->onUpdate("NO ACTION");
            $table->string('cr_userid');
            $table->string('edited_userid')->nullable()->default(null);
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
        Schema::dropIfExists('competitor_details_branches');
    }
};
