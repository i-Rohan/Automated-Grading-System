@extends('layouts.app')

@section('content')
    <div align="center">
        <img src="{{URL::to('/')}}/images/bmu_logo.png" alt="BMU Logo" class="img-responsive" height="150"
             width="150"/>
    </div>
    <script>
        console.log("{{Auth::user()->authority_level}}");
    </script>
    @if(Auth::user()->authority_level!="admin")
        <br>
        <br>
        <div align="center">
            Unauthorised Access!
            <br>
            <a href="{{URL::to('/')}}">
                Go Back to Home.
            </a>
        </div>
    @else
        @if(Session::has('message'))
            <div align="center">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Add Subject</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ URL::to('/') }}/home/admin/add_subject">
                                {{csrf_field()}}
                                <div class="form-group{{ $errors->has('subject_name') ? ' has-error' : '' }}">
                                    <label for="subject_name" class="col-md-4 control-label">Subject Name</label>
                                    <div class="col-md-6">
                                        <input id="subject_name" type="text" class="form-control" name="subject_name"
                                               value="{{ old('subject_name') }}"
                                               required autofocus>
                                        @if ($errors->has('subject_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('subject_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="teacher_id" class="col-md-4 control-label">Teacher</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select name="teacher_id" class="form-control"
                                                    value="{{ old('teacher_id') }}" required>
                                                <option value=""></option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="batch" class="col-md-4 control-label">Batch</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select name="batch" class="form-control"
                                                    value="{{ old('batch') }}" required>
                                                <option value=""></option>
                                                <option value="2014">2014</option>
                                                <option value="2015">2015</option>
                                                <option value="2016">2016</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sem" class="col-md-4 control-label">Semester</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select name="sem" class="form-control"
                                                    value="{{ old('sem') }}" required>
                                                <option value=""></option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="discipline" class="col-md-4 control-label">Discipline</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select id="discipline" name="discipline" class="form-control"
                                                    value="{{ old('discipline') }}" required>
                                                <option value=""></option>
                                                <option value="btech">B.Tech</option>
                                                <option value="bba">BBA</option>
                                                <option value="bcom">B.Com</option>
                                                <option value="mba">MBA</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="stream" class="col-md-4 control-label">Stream</label>
                                    <label>
                                        <input type="checkbox" name="stream[]" value="csc"
                                               class="form-control">CSC
                                        <input type="checkbox" name="stream[]" value="cse"
                                               class="form-control">CSE
                                        <input type="checkbox" name="stream[]" value="ece"
                                               class="form-control">ECE
                                        <input type="checkbox" name="stream[]" value="me"
                                               class="form-control">ME
                                        <input type="checkbox" name="stream[]" value="ce"
                                               class="form-control">CE
                                    </label>
                                </div>

                                <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                                    <label for="credits" class="col-md-4 control-label">Credits</label>
                                    <div class="col-md-6">
                                        <input id="credits" type="number" class="form-control" name="credits"
                                               value="{{ old('credits') }}" required min="0" max="100">
                                        @if ($errors->has('credits'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('credits') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Add Subject
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection