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
        //length of table name - 'bid_management_work_order_communication_files_subs' is too long for create foreign key.  so table name changed as 'communication_files_subs' instead of 'bid_management_work_order_communication_files_subs'  

        Schema::create('communication_files_subs', function (Blueprint $table) {
            $table->id();
            $table->string('randomno');
            $table->bigInteger('bidid')->unsigned();
            $table->foreign('bidid')->references('bidid')->on('bid_management_work_order_communication_files')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->string('comfile')->default('');
            $table->string('filetype')->default('');
            $table->integer('createdby_userid');
            $table->integer('updatedby_userid')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('bid_management_work_order_communication_files_subs');
    }
};
