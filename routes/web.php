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

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('/home/teacher/subject/{subject_id}_{stream}', ['as' => 'teacher.subject.show', 'uses' =>
    'TeacherSubjectController@show']);

Route::get('/home/teacher/subject/{subject_id}_{stream}/add_assessment', ['as' => 'teacher.add_assessment.show',
    'uses' => 'AddAssessmentController@show']);

Route::post('/home/teacher/subject/{id}/add_assessment', ['as' => 'teacher.add_assessment.create',
    'uses' => 'AddAssessmentController@create']);

Route::get('/home/teacher/subject/{subject_id}_{stream}/assessment/{assessment_id}', ['as' => 'teacher.assessment.show',
    'uses' => 'AssessmentController@show']);

Route::post('/home/teacher/subject/{id1}/assessment/{id2}', ['as' => 'teacher.assessment.create',
    'uses' => 'AssessmentController@create']);

Route::get('/home/admin/add_subject', ['as' => 'admin.add_subject', 'uses' => 'AddSubjectController@index']);

Route::post('/home/admin/add_subject', ['as' => 'admin.add_subject.create', 'uses' => 'AddSubjectController@create']);

Route::post('/home/teacher/subject/add_marks', ['as' => 'teacher.add_marks.create',
    'uses' => 'AddOrEditMarksController@create']);

Route::get('/home/student/subject/{subject_id}', ['as' => 'student.subject.show', 'uses' => 'StudentSubjectController@show']);