<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Marks;
use App\Subjects;
use Illuminate\Http\Request;
use Log;

class AddOrEditAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teacher.add_assessment');
    }

    public function show($subject_id, $stream)
    {
        $subject = Subjects::where('id', $subject_id)->get(array('id', 'subject_name', 'teacher_id', 'discipline',
            'stream', 'sem', 'batch'))->first();
        $assessments = Assessments::where('subject_id', $subject_id)->get();
        return view('teacher.add_assessment')->with('subject', $subject)->with('stream', $stream)->with('assessments', $assessments);
    }

    public function showEdit($subject_id, $stream, $assessment_id)
    {
        $assessment = Assessments::where('id', $assessment_id)->get()->first();
        $assessments = Assessments::where('subject_id', $subject_id)->get();
        return view('teacher.edit_assessment')->with('subject_id', $subject_id)->with('stream', $stream)->with('assessment', $assessment)->with('assessments', $assessments);
    }

    protected function create(Request $request)
    {
        $this->validate($request, array(
            'assessment_name' => 'required|max:255|min:3',
        ));
        $assessment = new Assessments;
        $assessment->subject_id = $request->subject_id;
        $assessment->assessment_name = $request->assessment_name;
        $assessment->weightage = $request->weightage;
        $assessment->max_marks = $request->max_marks;
        $assessment->save();
        return redirect()->route('teacher.subject.show', array($request->id, $request->stream))->with('message', 'Successfully Added!');
    }

    protected function edit(Request $request)
    {
        $success = Assessments::where('id', $request->assessment_id)->update(array('assessment_name' => $request->assessment_name, 'weightage' => $request->weightage, 'max_marks' => $request->max_marks));

        if ($success)
            return redirect()->route('teacher.assessment.show', array($request->subject_id, $request->stream, $request->assessment_id))->with('message', 'Successfully Updated!');
        else
            return redirect()->route('teacher.edit_assessment.showEdit', array($request->subject_id, $request->stream, $request->assessment_id))->with('message', 'Error in updating\nPlease try again...!');
    }

    protected function delete(Request $request)
    {
        Assessments::where('id', $request->assessment_id)->delete();
        Marks::where('assessment_id', $request->assessment_id)->delete();
        return redirect()->route('teacher.subject.show', array($request->subject_id, $request->stream))->with('message', 'Successfully Deleted!');
    }
}