<?php

namespace App\Http\Controllers;

use App\Assessments;
use App\Marks;
use App\Subjects;
use App\User;
use Illuminate\Http\Request;

class AddOrEditSubjectController extends Controller
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

    public function show_all()
    {
        $subjects = Subjects::all();
        $teachers = User::where('authority_level', 'teacher')->get();
        return view('admin.subjects')->with('teachers', $teachers)->with('subjects', $subjects);
    }

    public function show($id)
    {
        $teachers = User::where('authority_level', 'teacher')->get();
        $subject = Subjects::where('id', $id)->get()->first();
        return view('admin.edit_subject')->with('teachers', $teachers)->with('subject', $subject);
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
        return redirect()->route('home')->with('message', 'Successfully added!');
    }

    protected function edit(Request $request)
    {
        Subjects::where('id', $request->subject_id)->update(array('batch' => $request->batch, 'sem' => $request->sem,
            'teacher_id' => $request->teacher_id, 'credits' => $request->credits, 'subject_name' => $request->subject_name,
            'stream' => json_encode($request->stream)));
        return redirect()->route('admin.subjects', array('batch' => 'Any', 'sem' => 'Any'))->with('message', 'Successfully Updated!');
    }

    protected function delete(Request $request)
    {
        Subjects::where('id', $request->subject_id)->delete();
        Assessments::where('subject_id', $request->subject_id)->delete();
        Marks::where('subject_id', $request->subject_id)->delete();
        return redirect()->route('admin.subjects', array('batch' => 'Any', 'sem' => 'Any'))->with('message', 'Successfully Deleted!');
    }
}