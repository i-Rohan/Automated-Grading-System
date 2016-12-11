@extends('layouts.app')

@section('content')
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
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Add Assessment</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{URL::to('home/teacher/subject/')}}/{{$subject->id}}/add_assessment">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input type="hidden" id="subject_id" class="form-control" name="subject_id"
                                           value="{{$subject->id}}">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" id="stream" class="form-control" name="stream"
                                           value="{{$stream}}">
                                </div>
                                <div class="form-group{{ $errors->has('assessment_name') ? ' has-error' : '' }}">
                                    <label for="assessment_name" class="col-md-4 control-label">Assessment Name</label>
                                    <div class="col-md-6">
                                        <input id="assessment_name" type="text" class="form-control"
                                               name="assessment_name"
                                               value="{{ old('assessment_name') }}"
                                               required autofocus>
                                        @if ($errors->has('assessment_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('assessment_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <?php
                                $max = 0;
                                for ($i = 0; $i < count($assessments); $i++)
                                    $max += $assessments[$i]->weightage;
                                $max = 100 - $max;
                                ?>

                                <div class="form-group">
                                    <label for="weightage" class="col-md-4 control-label">Weightage</label>
                                    <div class="col-md-6">
                                        <input id="weightage" type="number" class="form-control" name="weightage"
                                               value="{{ old('weighatge') }}" required min="1" max="{{$max}}"
                                               step="any">
                                    </div>
                                    %
                                </div>

                                <div class="form-group">
                                    <label for="max_marks" class="col-md-4 control-label">Max Marks</label>
                                    <div class="col-md-6">
                                        <input id="max_marks" type="number" class="form-control" name="max_marks"
                                               value="{{ old('max_marks') }}" required min="1" step="any">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Add Assessment
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