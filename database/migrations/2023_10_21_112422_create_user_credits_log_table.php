<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCreditsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credits_log', function (Blueprint $table) {
	    $table->increments('user_credits_log_id');
            $table->integer('user_credits_id');
            $table->integer('parent_id');
            $table->integer('user_id');
            $table->bigInteger('provided_credits_count');
            $table->string('credit_comments', 100);
            $table->char('uc_log_status', 1);
            $table->timestamp('uc_log_entry_date');
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
        Schema::dropIfExists('user_credits_log');
    }
}
