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
        Schema::table('expenses_approvals', function (Blueprint $table) {
            //
            $table->integer('mainid')->unsigned();
            $table->foreign('mainid')->references('id')->on('other_expenses')->onDelete('restrict')->onUpdate('NO ACTION')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses+approvals', function (Blueprint $table) {
            //
        });
    }
};
