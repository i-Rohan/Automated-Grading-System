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
        <?php $color_array = array('#1abc9c', '#2ecc71', '#9b59b6', '#34495e', '#f1c40f', '#e67e22', '#e74c3c');
        $random_color = rand(0, count($color_array) - 1);
        ?>
        <div align="center">
            <div class="panel panel-subject" align="center"
                 style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                <div class="subject-name">
                    {{$subject->subject_name}}
                </div>
                <div class="teacher-name">
                    <div class="teacher-name">
                        {{$subject->batch}} Batch
                        <br>
                        @if($subject->sem==1)
                            {{$subject->sem}}st
                        @elseif($subject->sem==2)
                            {{$subject->sem}}nd
                        @elseif($subject->sem==3)
                            {{$subject->sem}}rd
                        @else
                            {{$subject->sem}}th
                        @endif
                        Semester
                        <br>
                        {{strtoupper($subject->discipline)}} {{strtoupper($stream)}}
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
                            <div class="panel-heading">Overall Result</div>
                            <div class="panel-body" align="left">
                                <label class="col-md-4 control-label">Name</label>
                                <label class="col-md-6">Marks</label>
                                <br>
                                <br>
                                @foreach($students as $student)
                                    <div style="background-color: white; border-color: #3498db">
                                        <label class="col-md-4 control-label">{{$student->name}}</label>
                                        <?php
                                        $temp = 0;
                                        $total = 0;
                                        for ($i = 0; $i < count($marks); $i++) {
                                            if ($marks[$i]->student_id == $student->id) {
                                                for ($j = 0; $j < count($assessments); $j++) {
                                                    if ($marks[$i]->assessment_id == $assessments[$j]->id) {
                                                        $temp += $marks[$i]->marks / $assessments[$j]->max_marks * $assessments[$j]->weightage;
                                                        $total += $assessments[$j]->weightage;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <label class="col-md-6">{{$temp}}/{{$total}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif
@endsection