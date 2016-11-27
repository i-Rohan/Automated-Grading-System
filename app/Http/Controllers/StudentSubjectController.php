<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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
        return view('student.subject', array('subject_id' => $subject_id))->with('subject', $subject)->with('teacher', $teacher);
    }
}
