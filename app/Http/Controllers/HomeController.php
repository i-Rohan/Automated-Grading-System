<?php

namespace App\Http\Controllers;

use App\Subjects;
use App\User;

class HomeController extends Controller
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
        $subjects = Subjects::all();
        $users = User::where('authority_level', 'teacher')->get(array('id', 'name'));

        return view('home')->with('subjects', $subjects)->with('users', $users);
    }
}