<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id');
            $table->enum('batch', array(2014, 2015, 2016));
            $table->enum('sem', array(1, 2, 3, 4, 5, 6, 7, 8));
            $table->integer('assessment_id');
            $table->string('student_id', 8);
            $table->enum('stream', array('', 'csc', 'cse', 'me', 'ece'));
            $table->float('marks');
            $table->timestamps();
            $table->unique(array('subject_id', 'batch', 'sem', 'assessment_id', 'student_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks');
    }
}
