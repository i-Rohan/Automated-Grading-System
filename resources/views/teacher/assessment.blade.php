@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 0 300px 0;
        }
    </style>
    {{--<div align="center">--}}
    {{--<img src="{{URL::to('/')}}/images/bmu_logo.png" alt="BMU Logo" class="img-responsive" height="150"--}}
    {{--width="150"/>--}}
    {{--</div>--}}
    @if(Auth::user()->authority_level!="teacher")
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
        <?php $count = 0;?>
        <div align="center">
            <div class="panel panel-subject" align="center">
                <div class="subject-name">
                    {{$assessment->assessment_name}}
                </div>
                <div class="teacher-name">
                    <div class="teacher-name">
                        Weightage: {{$assessment->weightage}}%
                        <br>
                        Max Marks: {{$assessment->max_marks}}
                    </div>
                </div>
            </div>
        </div>
        @if(Session::has('message'))
            <div class="margin" align="center">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Enter/Edit Marks</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{URL::to('/')}}/home/teacher/subject/add_marks" id="{{$count}}">
                                {{csrf_field()}}
                                @foreach($users as $user)
                                    @if($subject->batch==$user->batch and $subject->sem==$user->sem and $stream==$user->stream)
                                        <?php $count++;?>
                                        <input type="hidden" id="subject_id[]" class="form-control" name="subject_id[]"
                                               value="{{$subject->id}}" style="display: none;">
                                        <input type="hidden" id="batch[]" class="form-control" name="batch[]"
                                               value="{{$subject->batch}}" style="display: none;">
                                        <input type="hidden" id="sem[]" class="form-control" name="sem[]"
                                               value="{{$subject->sem}}" style="display: none;">
                                        <input type="hidden" id="assessment_id[]" class="form-control"
                                               name="assessment_id[]" value="{{$assessment->id}}"
                                               style="display: none;">
                                        <input type="hidden" id="student_id[]" class="form-control" name="student_id[]"
                                               value="{{$user->id}}" style="display: none;">
                                        <input type="hidden" id="stream[]" class="form-control" name="stream[]"
                                               value="{{$stream}}" style="display: none;">
                                        <div class="form-group">
                                            <label for="{{$user->id}}"
                                                   class="col-md-4 control-label">{{$user->name}}</label>
                                            <div class="col-md-6">
                                                <?php $temp = 0;
                                                for ($i = 0; $i < count($marks); $i++) {
                                                    if ($marks[$i]->batch == $user->batch &&
                                                            $marks[$i]->sem == $user->sem &&
                                                            $marks[$i]->stream == $user->stream &&
                                                            $assessment->id == $marks[$i]->assessment_id &&
                                                            $marks[$i]->student_id == $user->id
                                                    ) {
                                                        $temp = $marks[$i]->marks;
                                                    }
                                                }
                                                ?>
                                                <input id="marks[]" type="number" class="form-control"
                                                       name="marks[]" value="{{$temp}}" min="0"
                                                       max="{{$assessment->max_marks}}" step="any" required>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div align="center">
            <a href="#">
                <div class="panel panel-subject" align="center">
                    <div class="subject-name">
                        Edit Assessment
                    </div>
                </div>
            </a>
        </div>
    @endif
@endsection