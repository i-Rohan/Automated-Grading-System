<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Http\Requests;
use App\Subjects;

class TeacherSubjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('teacher.subject');
    }

    public function show($subject_id, $stream)
    {
        $subject = Subjects::where('id', $subject_id)->get(array('id', 'subject_name', 'teacher_id', 'discipline',
            'stream', 'sem', 'batch'))->first();
        $assessments = Assessments::all();
        return view('teacher.subject')->with('subject', $subject)->with('assessments', $assessments)->with('stream', $stream);
    }
}