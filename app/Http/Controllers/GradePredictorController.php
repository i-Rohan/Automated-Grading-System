<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Http\Requests;
use App\Marks;
use App\Subjects;
use App\User;
use Illuminate\Support\Facades\Auth;

class GradePredictorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subjects = Subjects::where(array('batch' => Auth::user()->batch, 'sem' => Auth::user()->sem))->get();
        $assessments = Assessments::all();
        $marks = Marks::where(array('batch' => Auth::user()->batch, 'sem' => Auth::user()->sem))->get();
        $students = User::where(array('batch' => Auth::user()->batch, 'sem' => Auth::user()->sem))->get();
        return view('student.grade_predictor')->with('subjects', $subjects)->with('assessments', $assessments)
            ->with('marks', $marks)->with('students', $students);
    }
}
