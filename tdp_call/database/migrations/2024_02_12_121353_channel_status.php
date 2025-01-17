<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChannelStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_status', function (Blueprint $table) {
            $table->increments('s_no');
            $table->integer('server_id');
            $table->string('channel_name', 10);
            $table->integer('channel_id');
            $table->integer('sim');
            $table->integer('sim_a');
            $table->integer('sim_b');
            $table->integer('sim_c');
            $table->integer('sim_d');
            $table->integer('rssi');
            $table->string('sim_number', 15);
            $table->string('cops', 30);
            $table->string('state', 15);
            $table->timestamp('last_update_time');
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
        Schema::dropIfExists('channel_status');
    }
}
