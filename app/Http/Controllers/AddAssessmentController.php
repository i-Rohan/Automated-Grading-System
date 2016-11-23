<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Subjects;
use Illuminate\Http\Request;

class AddAssessmentController extends Controller
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
        return view('teacher.add_assessment');
    }

    public function show($subject_id, $stream)
    {
        $subject = Subjects::where('id', $subject_id)->get(array('id', 'subject_name', 'teacher_id', 'discipline',
            'stream', 'sem', 'batch'))->first();
        return view('teacher.add_assessment')->with('subject', $subject)->with('stream', $stream);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return Assessments
     * @internal param array $data
     */
    protected function create(Request $request)
    {
        $this->validate($request, array(
            'assessment_name' => 'required|max:255|min:3',
            'weightage' => 'required|integer|min:1',
            'max_marks' => 'required|integer|min:1',
        ));
        $assessment = new Assessments;
        $assessment->subject_id = $request->subject_id;
        $assessment->assessment_name = $request->assessment_name;
        $assessment->weightage = $request->weightage;
        $assessment->max_marks = $request->max_marks;
        $assessment->save();
        return redirect()->route('teacher.subject.show', array($request->id, $request->stream))->with('message', 'Successfully Added!');
    }
}
