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
        Schema::create('menu_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_role_id')->default(0);
            $table->string('name','50');
            $table->string('icoClass','50')->Nullable();
            $table->string('dashboard_ico_class','100')->Nullable();
            $table->integer('status')->default(1);
            $table->string('menuLink','100')->nullable();
            $table->string('aliasName','100')->nullable();
            $table->smallInteger('sorting_order')->default(0);
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
        Schema::dropIfExists('menu_modules');
    }
};
