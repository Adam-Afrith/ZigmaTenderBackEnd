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
        Schema::create('sub_module_menus', function (Blueprint $table) {
            $table->id();
            $table->integer('user_role_id')->default(0);
            $table->integer('parentModuleID');
            $table->integer('parentSubModuleID')->default(0);
            $table->integer('sorting_order')->default(0);
            $table->string('name','50');
            $table->string('menuLink')->nullable();
            $table->string('parentUClass','45')->nullable();
            $table->string('parentLClass','45')->nullable();
            $table->string('icoClass','100')->default(null);
            $table->string('aliasName','45')->nullable();
            $table->smallInteger('status')->default(1);
            $table->integer('createdby');
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
        Schema::dropIfExists('sub_module_menus');
    }
};
