<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('from');
            $table->string('to');
            $table->integer('validity');
            $table->tinyInteger('is_valid');
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
        Schema::dropIfExists('invites');
    }
}
