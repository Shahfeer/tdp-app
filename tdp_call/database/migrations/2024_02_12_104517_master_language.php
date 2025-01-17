<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_language', function (Blueprint $table) {
            $table->increments('language_id');
            $table->string('language_name', 20)->nullable();
            $table->string('language_code', 10)->unique();
            $table->char('language_status', 1)->nullable();
            $table->timestamp('language_entdate')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_language');
    }
}
