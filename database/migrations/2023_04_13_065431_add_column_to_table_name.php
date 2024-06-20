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
        Schema::table('other_expenses', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_expenses', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('expenses_app_id')->nullable();
            $table->foreign('expenses_app_id')->nullable()->references('id')->on('expenses_approvals')->onDelete('restrict')->onUpdate('NO ACTION');
        
        });
    }
};