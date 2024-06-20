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
        Schema::create('customer_creation_s_w_m_project_statuses', function (Blueprint $table) {
           
                $table->id();
                $table->bigInteger('projecttype')->unsigned();
                $table->foreign('projecttype')->references('id')->on('project_types')->restrictOnDelete()->onUpdate("NO ACTION");
                $table->integer('status');
                $table->integer('vendortype');
                $table->string('vendor'); 
                $table->bigInteger('projectstatus')->unsigned();
                $table->foreign('projectstatus')->references('id')->on('project_statuses')->restrictOnDelete()->onUpdate("NO ACTION");
                $table->string('projectvalue');
                $table->date('duration1');
                $table->date('duration2'); 
                $table->integer('createdby');
                $table->integer('updatedby');
                $table->bigInteger('mainid')->unsigned();
                $table->foreign('mainid')->references('id')->on('customer_creation_profiles')->onDelete('cascade')->onUpdate("NO ACTION");
                $table->integer('delete_status');   
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
        Schema::dropIfExists('customer_creation_s_w_m_project_statuses');
    }
};
