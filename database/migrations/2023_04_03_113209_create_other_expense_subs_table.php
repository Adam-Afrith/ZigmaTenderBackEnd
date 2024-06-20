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
        Schema::create('other_expense_subs', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->integer("mainid")->unsigned();
            $table->foreign ('mainid')->references('id')->on('other_expenses')->OnDelete("CASCADE")->onUpdate("NO ACTION");
            $table->enum('need_call_against_expense',['0','1'])->default('0');
            $table->unsignedBigInteger("customer_id")->nullable();
            $table->foreign ('customer_id')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('call_no')->nullable()->unsigned();
            $table->foreign ('call_no')->references('id')->on('call_log_creations')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger("expense_type_id")->unsigned();
            $table->foreign ('expense_type_id')->references('id')->on('expense_types')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->decimal('amount',10,2);
            $table->text("description_sub")->nullable();
            $table->string('originalfilename')->nullable();
            $table->string('filetype')->nullable();
            $table->string('filesize')->nullable();
            $table->string('hasfilename')->nullable();
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
        Schema::dropIfExists('other_expense_subs');
    }
};
