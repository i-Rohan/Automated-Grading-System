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
        <?php $color_array = array('#16a085', '#2980b9', '#2c3e50', '#f39c12', '#c0392b', '#7f8c8d', '#27ae60',
            '#8e44ad', '#d35400', '#bdc3c7');
        $random_color = rand(0, count($color_array) - 1);

        // Function to calculate square of value - mean
        function sd_square($x, $mean)
        {
            return pow($x - $mean, 2);
        }

        // Function to calculate standard deviation (uses sd_square)
        function sd($array)
        {
            // square root of sum of squares divided by N
            return sqrt(array_sum(array_map("sd_square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / (count($array)));
        }

        $percentage_array = [];
        for ($i = 0; $i < count($all_subject_students); $i++) {
            $mark = 0.0;
            $total = 0.0;
            for ($j = 0; $j < count($marks); $j++)
                for ($k = 0; $k < count($assessments); $k++)
                    if ($marks[$j]->student_id == $all_subject_students[$i]->id and $marks[$j]->assessment_id == $assessments[$k]->id) {
                        $mark += $marks[$j]->marks;
                        $total += $assessments[$k]->max_marks;
                    }
            $percentage = $mark / $total * 100;
            array_push($percentage_array, $percentage);
        }
        sort($percentage_array);
        $count = count($percentage_array);

        $percentage_array_without_failures = [];
        for ($i = 0; $i < count($percentage_array); $i++)
            if ($percentage_array[$i] >= 40)
                array_push($percentage_array_without_failures, $percentage_array[$i]);

        $first = round(.25 * ($count + 1)) - 1;
        $second = ($count % 2 == 0) ? ($percentage_array[($count / 2) - 1] + $percentage_array[$count / 2]) / 2 : $second = $percentage_array[($count + 1) / 2];
        $third = round(.75 * ($count + 1)) - 1;
        $iqr = $percentage_array[$third] - $percentage_array[$first];
        $start = $percentage_array[$first] - 1.5 * $iqr;
        $stop = $percentage_array[$third] + 1.5 * $iqr;

        $percentage_array_without_outliers = [];
        for ($i = 0; $i < count($percentage_array_without_failures); $i++)
            if ($percentage_array_without_failures[$i] >= $start and $percentage_array_without_failures[$i] <= $stop)
                array_push($percentage_array_without_outliers, $percentage_array_without_failures[$i]);

        $mean = array_sum($percentage_array_without_outliers) / count($percentage_array_without_outliers);
        $stdev = sd($percentage_array_without_outliers);
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
                                <label class="col-md-3">
                                    <div style="font-weight: bolder; color: black">
                                        Name
                                    </div>
                                </label>
                                <label class="col-md-3">
                                    <div style="font-weight: bolder; color: black">
                                        Percentage
                                    </div>
                                </label>
                                <label class="col-md-3">
                                    <div style="font-weight: bolder; color: black">
                                        Grade
                                    </div>
                                </label>
                                <br>
                                <br>
                                @foreach($students as $student)
                                    <div style="background-color: white; border-color: #3498db">
                                        <label class="col-md-3">{{$student->name}}</label>
                                        <?php
                                        $temp = 0.0;
                                        $total = 0.0;
                                        $count = 0.0;
                                        for ($i = 0; $i < count($marks); $i++) {
                                            for ($j = 0; $j < count($assessments); $j++) {
                                                if ($marks[$i]->student_id == $student->id) {
                                                    if ($marks[$i]->assessment_id == $assessments[$j]->id) {
                                                        $temp += $marks[$i]->marks / $assessments[$j]->max_marks * $assessments[$j]->weightage;
                                                        $total += $assessments[$j]->weightage;
                                                    }
                                                } else {
                                                }
                                            }
                                        }
                                        if ($total != 0)
                                            $temp = $temp / $total * 100;
                                        ?>
                                        <label class="col-md-3">{{$temp}}%</label>
                                        <?php
                                        $grade = "";
                                        if ($temp >= $mean + 1.5 * $stdev)
                                            $grade = "A+";
                                        else if ($mean + 1.5 * $stdev > $temp and $temp >= $mean + 0.5 * $stdev)
                                            $grade = "A";
                                        else if ($mean + 0.5 * $stdev > $temp and $temp >= $mean)
                                            $grade = "B+";
                                        else if ($mean > $temp and $temp >= $mean - 0.5 * $stdev)
                                            $grade = "B";
                                        else if ($mean - 0.5 * $stdev > $temp and $temp >= $mean - $stdev)
                                            $grade = "C";
                                        else
                                            $grade = "D";
                                        if ($temp < 40)
                                            $grade = "F";
                                        ?>
                                        <label class="col-md-3">{{$grade}}</label>
                                    </div>
                                    <br>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection