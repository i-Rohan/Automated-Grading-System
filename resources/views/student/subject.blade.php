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
        $color_array = array('#16a085', '#27ae60', '#2980b9');
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
            $total_marks = 0.0;
            $total_weightage = 0.0;
            $percentage = 0.0;
            for ($j = 0; $j < count($marks); $j++)
                for ($k = 0; $k < count($assessments); $k++)
                    if ($marks[$j]->student_id == $all_subject_students[$i]->id and $marks[$j]->assessment_id == $assessments[$k]->id) {
                        $mark = $marks[$j]->marks;
                        $total = $assessments[$k]->max_marks;
                        $weightage = $assessments[$k]->weightage;
                        $total_weightage += $weightage;
                        $total_marks += $mark / $total * $weightage;
                    }
            if ($total_weightage != 0)
                $percentage = $total_marks / $total_weightage * 100;
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

        $mean = 0.0;
        $stdev = 0.0;
        if (count($percentage_array_without_outliers) != 0) {
            $mean = array_sum($percentage_array_without_outliers) / count($percentage_array_without_outliers);
            $stdev = sd($percentage_array_without_outliers);
        }
        $percentage = 0.0;
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
            <div class="col-md-12" style="margin-left: 12.5%;width: 75%;margin-top:5%;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr style="background-color: {{$color_array[$random_color]}};color: #fff">
                            <th>Assessment</th>
                            <th>Marks</th>
                            <th>Weightage</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        @foreach($assessments as $assessment)
                            <tbody>
                            <tr class="odd gradeX">
                                <td>{{$assessment->assessment_name}}</td>
                                @foreach($marks as $mark)
                                    @if($mark->student_id==Auth::user()->id && $mark->assessment_id==$assessment->id)
                                        <td>{{$mark->marks}}/{{$assessment->max_marks}}</td>
                                        <td>{{$assessment->weightage}}%</td>
                                        <td>{{$mark->marks/$assessment->max_marks*$assessment->weightage}}
                                            /{{$assessment->weightage}}</td>
                                    @endif
                                @endforeach
                            </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
            <?php
            $marks_obtained = 0.0;
            $total_weightage = 0.0;
            ?>
            @foreach($assessments as $assessment)
                @if($assessment->subject_id==$subject->id)
                    <?php
                    $flag = false;
                    ?>
                    @foreach($marks as $mark)
                        @if($mark->student_id==Auth::user()->id && $mark->assessment_id==$assessment->id)
                            <?php
                            $marks_obtained += $mark->marks / $assessment->max_marks * $assessment->weightage;
                            $total_weightage += $assessment->weightage;
                            $flag = true;
                            ?>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <div align="center">
                <div class="panel panel-subject" align="center"
                     style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                    <div class="subject-name">
                        Total Assessment
                    </div>
                    <?php
                    $temp = 0.0;
                    if ($total_weightage != 0)
                        $temp = $marks_obtained / $total_weightage * 100;
                    ?>
                    Percentage: {{round($temp,2)}}%
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