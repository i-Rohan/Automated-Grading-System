<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weightage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_id', 8);
            $table->string('assessment_name');
            $table->integer('weightage');
            $table->integer('max_marks');
            $table->unique(array('id', 'subject_id'));
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
        Schema::dropIfExists('weightage');
    }
}
