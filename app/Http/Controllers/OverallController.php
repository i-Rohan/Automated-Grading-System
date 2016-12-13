<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Http\Requests;
use App\Marks;
use App\Subjects;
use App\User;

class OverallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teacher.overall');
    }

    public function show($subject_id, $stream)
    {
        $subject = Subjects::where('id', $subject_id)->get()->first();
        $assessments = Assessments::where('subject_id', $subject_id)->get();
        $marks = Marks::where('subject_id', $subject_id)->get();
        $students = User::where(array('stream' => $stream, 'batch' => $subject->batch,
            'sem' => $subject->sem))->orderBy('name')->get();
        $all_subject_students = User::where(array('batch' => $subject->batch, 'sem' => $subject->sem))->get();
        return view('teacher.overall')->with('subject', $subject)->with('stream', $stream)
            ->with('assessments', $assessments)->with('marks', $marks)->with('students', $students)
            ->with('all_subject_students', $all_subject_students);
    }
}