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
        Schema::create('state_masters', function (Blueprint $table) {
            $table->id();
            $table->string("state_name")->unique();
            $table->string("state_code");
            $table->bigInteger("country_id")->unsigned();
            $table->foreign("country_id")->references("id")->on("country_masters")->restrictOnDelete()->onUpdate("NO ACTION");
            $table->string("state_status");
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
        // Schema::dropForeign(['country_id']);
        Schema::dropIfExists('state_masters');
        
    }
};
