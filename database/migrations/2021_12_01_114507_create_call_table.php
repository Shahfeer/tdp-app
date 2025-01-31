<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
        $table->increments('campaign_id');
        $table->binary('neron_id');
	    $table->integer('userId');
        $table->integer('ivr_id');
	    $table->string('campaign_name', 75);
	    $table->binary('mobile');
	    $table->integer('no_of_mobile_numbers');
        $table->string('context', 30);
        $table->integer('caller_id');
	    $table->integer('retry_count');
        $table->string('remarks', 30);
        $table->char('call_status', 1);
	    $table->timestamp('call_entry_time');
        $table->timestamp('call_start_time');
        $table->timestamp('call_completed_time');
        $table->char('cdrs_report', 1);
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
        Schema::dropIfExists('calls');
    }
}
