<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('user_logs', function (Blueprint $table) {
            $table->id('user_log_id');
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address', 30);
            $table->date('login_date');
            $table->timestamp('login_time');
            $table->timestamp('logout_time')->nullable();
            $table->char('user_log_status', 1)->default('I'); // Assuming 'I' is the default status
            $table->timestamp('user_log_entry_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
}
