<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromptMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prompt_masters', function (Blueprint $table) {
        $table->bigIncrements('prompt_id');
        $table->integer('user_id');
        $table->integer('ivr_id');
        $table->string('company_name', 10);
        $table->integer('states_id');
        $table->integer('language_id');
        $table->string('type', 5);
        $table->string('prompt_path', 100);
        $table->string('context', 50);
        $table->string('remarks', 50);
        $table->char('prompt_status', 1);
        $table->timestamp('prompt_entry_time');
        $table->timestamps(); // This line adds created_at and updated_at columns
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prompt_masters');
    }
}
