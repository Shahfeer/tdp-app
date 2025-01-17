<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('user_master', function (Blueprint $table) {
            $table->increments('user_master_id');
            $table->string('user_type', 20);
            $table->string('user_title', 20);
            $table->string('user_details', 50);
            $table->char('user_master_status', 1);
            $table->timestamp('user_entry_date');
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
        Schema::dropIfExists('user_master');
    }
}
