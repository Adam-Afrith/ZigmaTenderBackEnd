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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenid')->collation('utf8mb4_bin'); // Have to check in live
            $table->integer('userid');
            $table->integer('isLoggedIn');
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
};
