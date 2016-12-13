@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 10px 30px 10px;
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
                    <?php
                    $color_array = array('#16a085', '#27ae60', '#2980b9');
                    $color = 0?>
                    @foreach($subjects as $subject)
                        @if($subject->sem==Auth::user()->sem and $subject->discipline==Auth::user()->discipline and
                        $subject->batch==Auth::user()->batch)
                            @foreach(json_decode((html_entity_decode($subject->stream, true))) as $stream)
                                @if($stream==Auth::user()->stream)
                                    <a href="{{ route('student.subject', array( 'subject_id'=>$subject->id))}}">
                                        <div class="panel panel-subject" align="center"
                                             style="background-color: {{$color_array[$color]}}; border-color: {{$color_array[$color]}}">
                                            <div class="subject-name">
                                                {{$subject->subject_name}}
                                            </div>
                                            <div class="teacher-name">
                                                @foreach($users as $user)
                                                    @if($user->id==$subject->teacher_id)
                                                        {{$user->name}}
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </a>
                                    <?php
                                    if ($color == count($color_array) - 1)
                                        $color = 0;
                                    else
                                        $color++;
                                    ?>
                                @endif
                            @endforeach
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
                    <?php
                    $color_array = array('#16a085', '#27ae60', '#2980b9');
                    $color = 0?>
                    @foreach($subjects as $subject)
                        @if($subject->teacher_id==Auth::user()->id)
                            @foreach(json_decode((html_entity_decode($subject->stream, true))) as $stream)
                                <a href="{{ route('teacher.subject.show', array( 'subject_id' => $subject->id,'stream'=>$stream)) }}">
                                    <div class="panel panel-subject" align="center"
                                         style="background-color: {{$color_array[$color]}}; border-color: {{$color_array[$color]}}">
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
                                            {{strtoupper($subject->discipline)}} {{strtoupper($stream)}}
                                        </div>
                                    </div>
                                </a>
                                <?php
                                if ($color == count($color_array) - 1)
                                    $color = 0;
                                else
                                    $color++;
                                ?>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @else
        @if(Session::has('message'))
            <div class="margin" align="center">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        @endif
        <div class="margin">
            <div class="panel panel-default">
                <div class="panel-heading" align="center">Available Actions</div>
                <div class="panel-body" align="center">
                    <a href="{{ route('admin.add_subject')}}">
                        <div class="panel panel-subject" align="center">
                            <div class="subject-name">
                                Add Subject
                            </div>
                        </div>
                    </a>
                    <a href="{{URL::to('/')}}/home/admin/subjects?batch=Any&sem=Any">
                        <div class="panel panel-subject" align="center">
                            <div class="subject-name">
                                Edit Subject
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection