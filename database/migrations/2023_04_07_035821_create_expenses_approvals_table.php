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

        Schema::create('expenses_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('mainid')->unsigned();
            $table->foreign('mainid')->references('id')->on('other_expenses')->onDelete('restrict')->onUpdate('NO ACTION')->default(null);
            $table->date('entry_date')->default(null);
            $table->string('ex_app_no');
            $table->unsignedBigInteger("Staff_id")->nullable();
            $table->unsignedBigInteger("approver_id")->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->text("note")->nullable();
            $table->enum('hr_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('hr_by')->nullable();
            $table->foreign('hr_by')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('NO ACTION');
            $table->date('hr_date')->nullable();
            $table->enum('ceo_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('ceo_by')->nullable();
            $table->foreign('ceo_by')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('NO ACTION');
            $table->date('ceo_date')->nullable();
            $table->enum('ho_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('ho_by')->nullable();
            $table->foreign('ho_by')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('NO ACTION');
            $table->date('ho_date')->nullable();
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
        Schema::dropIfExists('expenses_approvals');
    }
};
