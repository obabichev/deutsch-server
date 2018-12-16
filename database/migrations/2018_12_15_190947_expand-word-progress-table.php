<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandWordProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('word_progresses', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('word_id')->unsigned();
            $table->foreign('word_id')
                ->references('id')->on('words')
                ->onDelete('cascade');
            $table->boolean('learned')->default(false);
            $table->integer('mistakes')->unsigned()->default(0);
            $table->date('repeat')->nullable();
            $table->integer('successes')->unsigned()->default(0);
            $table->boolean('excellent')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('word_progresses', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'learned', 'mistakes', 'repeat', 'successes', 'excellent', 'word_id']);
        });
    }
}
