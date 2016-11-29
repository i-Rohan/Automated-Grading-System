<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('batch', array(2014, 2015, 2016));
            $table->enum('sem', array(1, 2, 3, 4, 5, 6, 7, 8));
            $table->enum('discipline', array('btech', 'bba', 'bcom', 'mba'));
            $table->string('stream');
            $table->string('subject_name');
            $table->string('teacher_id', 8);
            $table->integer('credits');
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
        Schema::dropIfExists('subjects');
    }
}