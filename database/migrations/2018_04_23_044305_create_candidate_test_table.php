<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->tinyInteger('is_valid');
            $table->integer('validity');
            $table->integer('candidate_id');
            $table->integer('test_id');
            $table->integer('started_at')->nullable();
            $table->integer('finished_at')->nullable();
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
        Schema::dropIfExists('candidate_test');
    }
}
