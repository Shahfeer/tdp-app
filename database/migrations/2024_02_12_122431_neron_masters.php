<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NeronMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neron_masters', function (Blueprint $table) {
            $table->increments('neron_id');
            $table->string('board_name', 15);
            $table->string('server_id', 50);
            $table->string('neron_client_id', 100);
            $table->string('ip_address', 50);
            $table->string('node_port', 50);
            $table->string('socket_port', 50);
            $table->char('neron_status', 1);
            $table->char('running_status', 1);
            $table->timestamp('neron_con_time'); // This line adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neron_masters');
    }
}
