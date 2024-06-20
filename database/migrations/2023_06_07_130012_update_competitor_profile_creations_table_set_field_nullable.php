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
        Schema::table('competitor_profile_creations', function (Blueprint $table) {
            $table->string('registrationType')->nullable()->change();
            $table->year('registerationYear')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->integer('pincode')->nullable()->change();
            $table->string('panNo',11)->nullable()->change();
            $table->bigInteger('mobile')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('gstNo')->nullable()->change();
            $table->string('directors')->nullable()->change();
            $table->string('companyType')->nullable()->change();
            $table->integer('manpower')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competitor_profile_creations', function (Blueprint $table) {
            $table->string('registrationType')->nullable()->change();
            $table->year('registerationYear')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->integer('pincode')->nullable()->change();
            $table->string('panNo')->nullable()->change();
            $table->bigInteger('mobile')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('gstNo')->nullable()->change();
            $table->string('directors')->nullable()->change();
            $table->string('companyType')->nullable()->change();
            $table->integer('manpower')->nullable()->change();
        });
    }
};
