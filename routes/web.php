<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/teacher/subject/{id}', ['as' => 'teacher.subject.show', 'uses' => 'TeacherSubjectController@show']);

Route::get('/teacher/subject/{id}/add', ['as' => 'teacher.add.show', 'uses' => 'AddAssessmentController@show']);

Route::post('/teacher/subject/{id}/add', ['as' => 'teacher.add.create', 'uses' => 'AddAssessmentController@create']);