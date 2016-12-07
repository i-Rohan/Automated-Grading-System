<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Http\Requests;
use App\Marks;
use App\Subjects;
use App\User;

class StudentSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('student.subject');
    }

    public function show($subject_id)
    {
        $subject = Subjects::where('id', $subject_id)->get()->first();
        $teacher = User::where('id', $subject->teacher_id)->get()->first();
        $all_subject_students = User::where(array('batch' => $subject->batch, 'sem' => $subject->sem))->get();
        $marks = Marks::where('subject_id', $subject_id)->get();
        $assessments = Assessments::where('subject_id', $subject_id)->get();
        return view('student.subject', array('subject_id' => $subject_id))->with('subject', $subject)->with('teacher',
            $teacher)->with('all_subject_students', $all_subject_students)->with('marks', $marks)->with('assessments',
            $assessments);
    }
}
