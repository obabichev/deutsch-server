<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Word::truncate();
        \App\Tag::truncate();

        Schema::create('translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pre')->nullable();
            $table->string('val');
            $table->string('post')->nullable();
            $table->integer('word_id')->unsigned();
            $table->foreign('word_id')
                ->references('id')->on('words')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('words', function (Blueprint $table) {
            $table->dropColumn('val_pre');
            $table->dropColumn('val_post');
            $table->dropColumn('tr_pre');
            $table->dropColumn('tr');
            $table->dropColumn('tr_post');
            $table->string('pre')->nullable();
            $table->string('post')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translations');

        Schema::table('words', function (Blueprint $table) {
            $table->string('val_pre')->nullable();
            $table->string('val_post')->nullable();
            $table->string('tr_pre')->nullable();
            $table->string('tr');
            $table->string('tr_post')->nullable();
            $table->dropColumn('pre')->nullable();
            $table->dropColumn('post')->nullable();
        });
    }
}
