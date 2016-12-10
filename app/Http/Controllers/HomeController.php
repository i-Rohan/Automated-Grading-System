<?php

namespace App\Http\Controllers;

use App\Subjects;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subjects = Subjects::all();
        $users = User::where('authority_level', 'teacher')->get(array('id', 'name'));

        return view('home')->with('subjects', $subjects)->with('users', $users);
    }
}