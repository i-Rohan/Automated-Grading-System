<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Http\Requests;
use App\Marks;
use App\Subjects;
use App\User;
use Log;

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
        $subject = Subjects::where('id', $subject_id)->get(array('id', 'subject_name', 'teacher_id', 'discipline',
            'stream', 'sem', 'batch'))->first();
        Log::info($subject);
        $assessments = Assessments::where('subject_id', $subject_id)->get();
        Log::info($assessments);
        $marks = Marks::where('subject_id', $subject_id)->get();
        Log::info($marks);
        $students = User::where(array('stream' => $stream, 'batch' => $subject->batch,
            'sem' => $subject->sem))->orderBy('name')->get();
        Log::info($students);
        return view('teacher.overall')->with('subject', $subject)->with('stream', $stream)->with('assessments',
            $assessments)->with('marks', $marks)->with('students', $students);
    }
}