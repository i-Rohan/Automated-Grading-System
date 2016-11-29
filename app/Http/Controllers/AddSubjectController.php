<?php

namespace App\Http\Controllers;

use App\Subjects;
use App\User;
use Illuminate\Http\Request;

class AddSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $teachers = User::where('authority_level', 'teacher')->orderBy('name')->get(array('id', 'name'));
        $subject_ids = Subjects::get(array('id'));
        return view('admin.add_subject')->with('teachers', $teachers)->with('subject_ids', $subject_ids);
    }

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
        $subject->credits = $request->credits;
        $subject->save();
        return redirect()->route('home')->with('message','Successfully added!');
    }
}