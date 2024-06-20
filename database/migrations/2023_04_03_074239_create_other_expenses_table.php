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
        Schema::create('other_expenses', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->string("expense_no");
            $table->date('entry_date')->format('Y/m/d');
            $table->bigInteger("executive_id")->unsigned(); //staff id
            $table->foreign ('executive_id')->references('id')->on('users')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->text("description")->nullable();
            $table->integer('created_by');
            $table->integer('edited_by')->nullable();
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
        Schema::dropIfExists('other_expenses');
    }
};
