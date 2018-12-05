<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandWordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('words', function (Blueprint $table) {
            $table->string('val_pre')->nullable();
            $table->string('val');
            $table->string('val_post')->nullable();
            $table->string('gender')->nullable();
            $table->string('tr_pre')->nullable();
            $table->string('tr');
            $table->string('tr_post')->nullable();
            $table->string('type')->nullable();
            $table->dropColumn('translation');
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('words', function (Blueprint $table) {
            $table->dropColumn('val_pre');
            $table->dropColumn('val');
            $table->dropColumn('val_post');
            $table->dropColumn('gender');
            $table->dropColumn('tr_pre');
            $table->dropColumn('tr');
            $table->dropColumn('tr_post');
            $table->dropColumn('type');
            $table->string('translation');
            $table->string('value');
        });
    }
}
