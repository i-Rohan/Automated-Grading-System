@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 5px 50px 25px;
        }
    </style>
    <div align="center">
        <img src="{{URL::to('/')}}/images/bmu_logo.png" alt="BMU Logo" class="img-responsive" height="150"
             width="150"/>
    </div>
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
        <div align="center">
            <div class="panel panel-subject" align="center">
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
        </div>
        @if(Session::has('message'))
            <div class="margin" align="center">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        @endif
        <div class="margin">
            <div class="panel panel-default">
                <div class="panel-heading" align="center">Assessments</div>
                <div class="panel-body" align="center">
                    @foreach($assessments as $assessment)
                        @if($assessment->subject_id==$subject->id)
                            <a href="{{ route('teacher.assessment.show', array( 'subject_id'=>$subject->id,'assessment_id' => $assessment->id,'stream'=>$stream)) }}">
                                <div class="panel panel-subject" align="center">
                                    <div class="subject-name">
                                        {{$assessment->assessment_name}}
                                    </div>
                                    <div class="teacher-name">
                                        Weightage: {{$assessment->weightage}}%
                                        <br>
                                        Max Marks: {{$assessment->max_marks}}
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div align="center">
            <a href="{{ route('teacher.add_assessment.show', array( 'subject_id' => $subject->id,'stream'=>$stream)) }}">
                <div class="panel panel-subject" align="center">
                    <div class="subject-name">
                        Add Assessment
                    </div>
                </div>
            </a>
        </div>
    @endif
@endsection