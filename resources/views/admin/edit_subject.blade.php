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
        <?php
        $color_array = array('#16a085', '#27ae60', '#2980b9');
        $random_color = rand(0, count($color_array) - 1);
        ?>
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
                                  action="{{URL::to('/')}}/home/admin/edit_subject/{{$subject->id}}/edit">
                                {{csrf_field()}}
                                <input type="hidden" class="form-control" name="subject_id" id="subject_id"
                                       value="{{$subject->id}}">
                                <div class="form-group">
                                    <label for="subject_name" class="col-md-4 control-label">Subject Name</label>
                                    <div class="col-md-6">
                                        <input id="subject_name" type="text" class="form-control" name="subject_name"
                                               value="{{$subject->subject_name}}"
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
                                            <select name="teacher_id" class="form-control" required id="teacher_id">
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
                                            <select name="batch" class="form-control" required id="batch">
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
                                            <select name="sem" class="form-control" id="sem" required>
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
                                            <select id="discipline" name="discipline" class="form-control">
                                                <option value=""></option>
                                                <option value="btech">B.Tech</option>
                                                <option value="bba">BBA</option>
                                                <option value="bcom">B.Com</option>
                                                <option value="mba">MBA</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <?php
                                $teacher_id = 0;
                                ?>
                                @foreach($teachers as $z)
                                    @if($subject->teacher_id==$z->id)
                                        <?php
                                        $teacher_id = $z->id;
                                        ?>
                                    @endif
                                @endforeach()

                                <script>
                                    SelectElement('teacher_id', '{{$teacher_id}}');
                                    SelectElement('batch', '{{$subject->batch}}');
                                    SelectElement('sem', '{{$subject->sem}}');
                                    SelectElement('discipline', '{{$subject->discipline}}');
                                    function SelectElement(id, value) {
                                        var element = document.getElementById(id);
                                        element.value = value;
                                    }
                                </script>

                                <div class="form-group">
                                    <label for="stream" class="col-md-4 control-label">Stream</label>
                                    <label>
                                        <input type="checkbox" name="stream[]" value="csc" id="csc"
                                               class="form-control">CSC
                                        <input type="checkbox" name="stream[]" value="cse" id="cse"
                                               class="form-control">CSE
                                        <input type="checkbox" name="stream[]" value="ece" id="ece"
                                               class="form-control">ECE
                                        <input type="checkbox" name="stream[]" value="me" id="me"
                                               class="form-control">ME
                                        <input type="checkbox" name="stream[]" value="ce" id="ce"
                                               class="form-control">CE
                                    </label>
                                </div>

                                <?php
                                $streams = json_decode((html_entity_decode($subject->stream, true)));
                                ?>
                                @foreach($streams as $stream)
                                    <script>
                                        check('{{$stream}}');
                                        function check(id) {
                                            document.getElementById(id).checked = true;
                                        }
                                    </script>
                                @endforeach

                                <div class="form-group">
                                    <label for="credits" class="col-md-4 control-label">Credits</label>
                                    <div class="col-md-6">
                                        <input id="credits" type="number" class="form-control" name="credits"
                                               value="{{$subject->credits}}" required min="0" step="1">
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
            <div align="center">
                <form role="form" method="POST"
                      action="{{URL::to('/')}}/home/admin/edit_subject/{{$subject->id}}/delete">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" name="subject_id" id="subject_id"
                           value="{{$subject->id}}" style="visibility: hidden;">
                    <button type="submit" class="panel panel-subject"
                            style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                        <div class="subject-name">
                            Delete Subject
                        </div>
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection