<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credits', function (Blueprint $table) {
	    $table->increments('user_credits_id');
            $table->integer('user_id');
            $table->integer('total_credits');
            $table->integer('used_credits');
            $table->integer('available_credits');
            $table->timestamp('expiry_date');
            $table->char('uc_status', 1);
            $table->timestamp('uc_entry_date');
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
        Schema::dropIfExists('user_credits');
    }
}
