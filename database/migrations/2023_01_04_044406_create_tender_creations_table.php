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
        Schema::create('tender_creations', function (Blueprint $table) {
            $table->id();
            $table->string('organisation');
            $table->bigInteger('customername')->unsigned();
            $table->foreign("customername")->references("id")->on("customer_creation_profiles")->onDelete("restrict")->onUpdate("NO ACTION");
            $table->date('nitdate');
            $table->bigInteger('tendertype')->unsigned();
            $table->foreign("tendertype")->references("id")->on("tender_type_masters")->onDelete("restrict")->onUpdate("NO ACTION");
            $table->integer('cr_userid');
            $table->integer('edited_userid')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('tender_creations');
    }
};
