<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('author');
            $table->string('supervisor');
            $table->string('email');
            $table->text('abstrack_en');
            $table->text('abstrack_id');
            $table->string('keyword');
            $table->string('cover');
            $table->string('bab_1');
            $table->string('bab_2');
            $table->string('bab_3');
            $table->string('bab_4');
            $table->string('bab_5');
            $table->string('bab_6');
            $table->string('lampiran');
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
        Schema::drop('articles');
    }
}
