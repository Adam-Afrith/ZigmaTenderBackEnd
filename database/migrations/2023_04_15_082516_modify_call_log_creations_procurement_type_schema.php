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
        Schema::table('call_log_creations', function (Blueprint $table) {
            $table->integer('procurement_type_id',10)->nullable()->default(null)->autoIncrement(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_log_creations', function (Blueprint $table) {
            $table->integer('procurement_type_id',10)->nullable()->default(null)->autoIncrement(false)->change();
        });
    }
};
