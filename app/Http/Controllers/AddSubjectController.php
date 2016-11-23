<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Subjects;
use App\User;
use Illuminate\Http\Request;

class AddSubjectController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $teachers = User::where('authority_level', 'teacher')->orderBy('name')->get(array('id', 'name'));
        $subject_ids = Subjects::get(array('id'));
        return view('admin.add_subject')->with('teachers', $teachers)->with('subject_ids', $subject_ids);
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
            'subject_name' => 'required|max:255|min:3',
        ));
        $subject = new Subjects;
        $subject->subject_name = $request->subject_name;
        $subject->discipline = $request->discipline;
        $subject->batch = $request->batch;
        $subject->sem = $request->sem;
        $subject->stream = json_encode($request->stream);
        $subject->teacher_id = $request->teacher_id;
        $subject->save();
        return redirect()->route('home');
    }
}