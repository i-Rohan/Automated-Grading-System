@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 5px 0 25px;
        }
    </style>
    {{--<div align="center">--}}
    {{--<img src="{{URL::to('/')}}/images/bmu_logo.png" alt="BMU Logo" class="img-responsive" height="150"--}}
    {{--width="150"/>--}}
    {{--</div>--}}
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
        <?php
        $color_array = array('#1abc9c', '#2ecc71', '#9b59b6', '#34495e', '#f1c40f', '#e67e22', '#e74c3c');
        $random_color = rand(0, count($color_array) - 1);
        $color = 0;
        if ($random_color == 0)
            $color = 1;

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
                        {{$teacher->name}}
                    </div>
                </div>
            </div>
        </div>
        <div align="center">
            @if(Session::has('message'))
                <div class="margin" align="center">
                    <div class="alert alert-info">
                        {{ Session::get('message') }}
                    </div>
                </div>
            @endif
            <div class="container">
                <div class="margin">
                    <div class="panel panel-default">
                        <div class="panel-heading">Overall Result</div>
                        <div class="panel-body" align="center">
                            <?php
                            $total_marks = 0.0;
                            $total_weightage = 0.0;
                            ?>
                            @foreach($assessments as $assessment)
                                @if($assessment->subject_id==$subject->id)
                                    <div class="panel panel-subject" align="center"
                                         style="background-color: {{$color_array[$color]}}; border-color: {{$color_array[$color]}}">
                                        <div class="subject-name">
                                            {{$assessment->assessment_name}}
                                        </div>
                                        <div class="teacher-name">
                                            Weightage: {{$assessment->weightage}}%
                                            <br>
                                            <?php $marks_obtained = 0.0;?>
                                            @foreach($marks as $mark)
                                                @if($mark->student_id==Auth::user()->id && $mark->assessment_id==$assessment->id)
                                                    <?php
                                                    $marks_obtained = $mark->marks;
                                                    $total_marks += $marks_obtained;
                                                    $total_weightage += $assessment->max_marks;
                                                    ?>
                                                    Marks Obtained: {{$mark->marks}}/{{$assessment->max_marks}}
                                                @endif
                                            @endforeach
                                            <br>
                                            {{$marks_obtained/$assessment->max_marks*$assessment->weightage}}
                                            /{{$assessment->weightage}}
                                        </div>
                                    </div>
                                    <?php
                                    $color++;
                                    while ($color == $random_color) {
                                        $color++;
                                        if ($color >= count($color_array))
                                            $color = 0;
                                    }
                                    ?>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div align="center">
                <div class="panel panel-subject" align="center"
                     style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                    <div class="subject-name">
                        Total Assessment
                    </div>
                    <?php $temp = $total_marks / $total_weightage * 100;?>
                    Percentage: {{round($total_marks/$total_weightage*100,2)}}%
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
                    <br>
                    Grade: {{$grade}}
                </div>
            </div>
        </div>
    @endif
@endsection