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
        Schema::create('bid_management_tender_or_bid_stauses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('bidid')->unsigned()->unique();
            $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");

            $table->enum('status',["Retender","Cancel", "Pending", "Completed"])->nullable()->default(null); 

            $table -> string('file_original_name')->nullable()->default('');
            $table -> string('file_new_name')->nullable()->default('');
            $table -> string('file_type')->nullable()->default('');
            $table -> double('file_size')->nullable()->default(0);
            $table -> string('ext')->nullable()->default('');

            $table->integer('created_by');
            $table->integer('edited_by')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_management_tender_or_bid_stauses');
    }
};
