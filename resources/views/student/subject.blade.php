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
    @if(Auth::user()->authority_level!="student")
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
    @endif
@endsection