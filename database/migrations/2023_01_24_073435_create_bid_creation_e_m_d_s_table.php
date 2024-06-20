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
        Schema::create('bid_creation_e_m_d_s', function (Blueprint $table) {
            $table->id();
            $table -> string('bankname')->nullable()->default('');
            $table -> string('bankbranch')->nullable()->default('');
            $table -> string('mode')->nullable()->default('');
            $table -> date('dateofsubmission')->nullable()->default(null);
            $table -> string('bgno')->nullable()->default('');
            $table -> string('ddno')->nullable()->default('');
            $table -> string('utrno')->nullable()->default('');
            $table -> date('dateofissue')->nullable()->default(null);
            $table -> date('expiaryDate')->nullable()->default(null);
            $table -> string('refno')->nullable()->default('');
            $table -> date('dateofpayment')->nullable()->default(null);
            $table -> string('value')->nullable()->default('');
            $table -> string('file_original_name')->nullable()->default('');
            $table -> string('file_new_name')->nullable()->default('');
            $table -> string('file_type')->nullable()->default('');
            $table -> double('file_size')->nullable()->default(0);
            $table -> string('ext')->nullable()->default('');
            $table -> bigInteger("bidCreationMainId")->unsigned();
            $table -> foreign("bidCreationMainId")->references("id")->on("bid_creation__creations")->onDelete("cascade")->onUpdate("NO ACTION");
            $table -> integer('createdby_userid');
            $table -> integer('updatedby_userid')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_creation_e_m_d_s');
    }
};
