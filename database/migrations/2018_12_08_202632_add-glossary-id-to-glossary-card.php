<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlossaryIdToGlossaryCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('glossary_cards', function (Blueprint $table) {
            $table->integer('glossary_id')->unsigned();
            $table->foreign('glossary_id')
                ->references('id')->on('glossaries')
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
        Schema::table('glossary_cards', function (Blueprint $table) {
            $table->dropColumn(['glossary_id']);
        });
    }
}
