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
    @if (Auth::user()->authority_level!="admin")
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
            <div class="margin" align="center">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        @endif
        <div class="margin">
            <form>
                <label>Batch</label>
                <label style="margin-right: 25px;margin-bottom: 25px;margin-top: 25px">
                    <select id="batch" name="batch">
                        <option value="Any">Any</option>
                        <option value="2014">2014</option>
                        <option value="2015">2015</option>
                        <option value="2016">2016</option>
                    </select>
                </label>
                <label>Sem</label>
                <label style="margin-right: 25px;margin-bottom: 25px;margin-top: 25px">
                    <select id="sem" name="sem">
                        <option value="Any">Any</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </label>
                <button class="btn btn-primary">Filter</button>
            </form>
            <script>
                SelectElement('batch', '{{$_GET['batch']}}');
                SelectElement('sem', '{{$_GET['sem']}}');
                function SelectElement(id, value) {
                    var element = document.getElementById(id);
                    element.value = value;
                }
            </script>
            <div class="panel panel-default">
                <div class="panel-heading" align="center">Available Actions</div>
                <div class="panel-body" align="center">
                    @foreach($subjects as $subject)
                        @if($subject->batch==$_GET['batch'] or $_GET['batch']=='Any')
                            @if($subject->sem==$_GET['sem'] or $_GET['sem']=='Any')
                                <a href="{{route('admin.edit_subject',array('id'=>$subject->id))}}">
                                    <div class="panel panel-subject" align="center">
                                        <div class="subject-name">
                                            {{$subject->subject_name}}
                                        </div>
                                        <div class="teacher-name">
                                            @foreach($teachers as $teacher)
                                                @if($teacher->id==$subject->teacher_id)
                                                    {{$teacher->name}}
                                                @endif
                                            @endforeach
                                            <br>
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
                                            <?php
                                            $streams = json_decode((html_entity_decode($subject->stream, true)));
                                            ?>
                                            @foreach($streams as $stream)
                                                {{strtoupper($stream)}}
                                            @endforeach
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection