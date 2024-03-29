<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Marks;
use App\Subjects;
use App\User;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teacher.assessment');
    }

    public function show($subject_id, $stream, $assessment_id)
    {
        $assessment = Assessments::where('id', $assessment_id)->first();
        $subject = Subjects::where('id', $subject_id)->first();
        $users = User::where(['batch' => $subject->batch, 'sem' => $subject->sem, 'stream' => $stream])->orderBy('name')->get();
        $marks = Marks::where('assessment_id', $assessment_id)->get();
        return view('teacher.assessment')->with('assessment', $assessment)->with('users', $users)->with('subject',
            $subject)->with('marks', $marks)->with('stream', $stream);
    }
}