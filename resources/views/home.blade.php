@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 5px 50px 100px;
        }
    </style>
    <div align="center">
        <img src="{{URL::to('/')}}/images/bmu_logo.png" alt="BMU Logo" class="img-responsive" height="150"
             width="150"/>
    </div>
    @if (Auth::user()->authority_level=="student")
        <div class="margin">
            <div class="panel panel-default">
                <div class="panel-heading" align="center">Your Courses</div>
                <div class="panel-body" align="center">
                    @foreach($subjects as $subject)
                        @if($subject->sem==Auth::user()->sem and $subject->discipline==Auth::user()->discipline and
                        $subject->stream==Auth::user()->stream and $subject->batch==Auth::user()->batch)
                            <a href="#">
                                <div class="panel panel-subject" align="center">
                                    <div class="subject-name">
                                        {{$subject->subject_name}}
                                    </div>
                                    <div class="teacher-name">
                                        @foreach($users as $user)
                                            @if($user->user_id==$subject->teacher_id)
                                                {{$user->name}}
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @elseif(Auth::user()->authority_level=="teacher")
        <div class="margin">
            <div class="panel panel-default">
                <div class="panel-heading" align="center">Your Subjects</div>
                <div class="panel-body" align="center">
                    @foreach($subjects as $subject)
                        @if($subject->teacher_id==Auth::user()->user_id)
                            <a href="{{ route('teacher.subject.show', array( 'id' => $subject->id)) }}">
                                <div class="panel panel-subject" align="center">
                                    <div class="subject-name">
                                        {{$subject->subject_name}}
                                    </div>
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
                                        {{strtoupper($subject->discipline)}} {{strtoupper($subject->stream)}}
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="margin">
            <div class="panel panel-default">
                <div class="panel-heading" align="center">Available Actions</div>
                <div class="panel-body" align="center">
                    <a href="https://media.giphy.com/media/DuaK1bQ8CbARi/giphy.gif">
                        <div class="panel panel-subject" align="center">
                            <div class="subject-name">
                                FUCK YOU!
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection