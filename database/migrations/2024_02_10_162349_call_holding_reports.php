<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CallHoldingReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_holding_reports', function (Blueprint $table) {
            $table->increments('call_holding_reprtid');
            $table->integer('user_id');
            $table->integer('campaign_id');
            $table->timestamp('campaign_date'); 
            $table->string('1_5_secs', 6);
            $table->string('6_10_secs', 6);
            $table->string('11_15_secs', 6);
            $table->string('16_20_secs', 6);
            $table->string('21_25_secs', 6);
            $table->string('26_30_secs', 6);
            $table->string('31_45_secs', 6);
            $table->string('46_60_secs', 6);
            $table->integer('total_calls');
            $table->integer('call_answered');
            $table->integer('call_not_answered');
            $table->char('call_holding_reprtstat', 1);
            $table->timestamp('call_holding_reprtdt'); // This line adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_holding_reports');
    }
}
