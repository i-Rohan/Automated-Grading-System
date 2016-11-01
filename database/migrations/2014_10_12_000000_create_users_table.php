<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('authority_level', array('admin', 'teacher', 'student'));
            $table->enum('batch', array('', 2014, 2015, 2016));
            $table->enum('sem', array('', 1, 2, 3, 4, 5, 6, 7, 8));
            $table->enum('discipline_id', array('', 'btech', 'bba', 'bcom', 'mba'));
            $table->enum('stream_id', array('', 'csc', 'cse', 'me', 'ece'));
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
