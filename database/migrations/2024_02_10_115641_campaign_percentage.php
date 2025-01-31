<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampaignPercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_percentage', function (Blueprint $table) {
            $table->increments('percentage_id');
                $table->integer('user_id');
                $table->integer('campaign_id');
                $table->integer('neron_id');
                $table->integer('total_mobileno_counts');
                $table->char('cam_percentage_status', 1);
                $table->timestamp('cam_percentage_entry_date');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_percentage');
    }
}
