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
        Schema::table('bid_creation__creations', function (Blueprint $table) {
            
            //this migration alteration has applied into bid_creation__creations migration file  

            // $table->double('projectperioddate1')->change();
            // $table->string('projectperioddate2')->change();
            // $table->double('quality')->default(null)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bid_creation__creations', function (Blueprint $table) {
            //
        });
    }
};
