<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTranslationToWordProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('word_progresses', function (Blueprint $table) {
            $table->integer('translation_id')->unsigned();
            $table->foreign('translation_id')
                ->references('id')->on('translations')
                ->onDelete('cascade');
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
            $table->dropColumn(['translation_id']);
        });
    }
}
