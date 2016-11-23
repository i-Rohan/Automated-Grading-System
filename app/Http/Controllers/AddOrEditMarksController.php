<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Marks;
use Illuminate\Http\Request;

class AddOrEditMarksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function create(Request $request)
    {
        for ($i = 0; $i < count($request->marks); $i++) {
            $subject_id = $request->subject_id;
            $batch = $request->batch;
            $sem = $request->sem;
            $assessment_id = $request->assessment_id;
            $student_id = $request->student_id;
            $stream = $request->stream;
            $mark = $request->marks;
            $marks = Marks::firstOrNew(array('subject_id' => $subject_id[$i], 'batch' => $batch[$i],
                'sem' => $sem[$i], 'assessment_id' => $assessment_id[$i], 'student_id' => $student_id[$i],
                'stream' => $stream[$i]));
            $marks->marks = $mark[$i];
            $marks->save();
        }
        $subject_id = $request->subject_id;
        $stream = $request->stream;
        $assessment_id = $request->assessment_id;
        return redirect()->route('teacher.assessment.show', array($subject_id[0], $stream[0], $assessment_id[0]))->with('message', 'Successfully Updated!');
    }
}