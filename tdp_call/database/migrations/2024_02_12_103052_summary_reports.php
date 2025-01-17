<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SummaryReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_reports', function (Blueprint $table) {
            $table->increments('summary_report_id');
            $table->integer('user_id');
            $table->timestamp('campaign_date'); 
            $table->integer('campaign_id');
            $table->integer('total_dialled');
            $table->integer('total_success');
            $table->integer('total_failed');
            $table->integer('total_busy');
            $table->integer('total_no_answer');
            $table->integer('first_attempt');
            $table->integer('retry_1');
            $table->integer('retry_2');
            $table->string('success_percentage', 6);
            $table->string('average_call_hold', 6);
            $table->char('summary_report_status', 1);
            $table->timestamp('summary_report_entdate'); // This line adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summary_reports');
    }
}
