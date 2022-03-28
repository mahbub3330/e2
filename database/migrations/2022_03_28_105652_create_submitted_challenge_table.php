<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittedChallengeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_challenge', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('challenge_id');
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('user_id');
            $table->string('score')->nullable();
            $table->string('try')->nullable();
            $table->string('flag')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('submitted_challenge');
    }
}
