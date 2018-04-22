<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateInviteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_token');
            $table->string('test_token');
            $table->string('to_email');
            $table->string('to_fullname');
            $table->string('from');
            $table->integer('test_id');
            $table->integer('test_validity');
            $table->integer('invite_validity');
            $table->integer('test_started_at')->nullable();
            $table->integer('test_finished_at')->nullable();
            $table->tinyInteger('is_email_token_valid');
            $table->tinyInteger('is_invite_token_valid');
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
        Schema::dropIfExists('candidate_invite');
    }
}
