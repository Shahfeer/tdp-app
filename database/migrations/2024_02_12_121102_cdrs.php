<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cdrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cdrs', function (Blueprint $table) {
            $table->increments('id');
                $table->string('campaignId', 50);
                $table->integer('neron_id');
                $table->string('accountcode', 15)->nullable();
                $table->string('src', 15)->nullable();
                $table->string('dst', 15);
                $table->string('clid', 30)->nullable();
                $table->string('channel', 20)->nullable();
                $table->timestamp('calldate')->nullable();
                $table->timestamp('answerdate')->nullable();
                $table->timestamp('last_call_time')->nullable();
                $table->timestamp('hangupdate')->nullable();
                $table->integer('billsec')->nullable();
                $table->string('disposition', 20)->nullable();
                $table->integer('retry_count');
                $table->string('amaflags', 10)->nullable();
                $table->string('recordurl', 50)->nullable();
                $table->string('direction', 5)->nullable();
                $table->timestamp('entry_date')->nullable();
                $table->char('cdrs_status', 1)->nullable();
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
        Schema::dropIfExists('cdrs');
    }
}
