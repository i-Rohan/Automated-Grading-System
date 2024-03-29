@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 5px 0 25px;
        }
    </style>
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
            for ($x = 0; $x < count(json_decode((html_entity_decode($subject->stream, true)))); $x++) {

                {
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
                                $total_marks += round($mark / $total * $weightage);
                            }
                    if ($total_weightage != 0)
                        $percentage = $total_marks / $total_weightage * 100;
                    array_push($percentage_array, $percentage);
                }
            }
        }
        sort($percentage_array);
        $count = count($percentage_array);

        $min = min($percentage_array);
        $average = 0.0;
        if (count($percentage_array))
            $average = array_sum($percentage_array) / count($percentage_array);
        $max = max($percentage_array);
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
            <div class="col-md-12" style="width: 100%;margin-top:5%;">
                <div class="table-responsive" style="margin-left: 12.5%;margin-right: 12.5%;">
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
                                        <td>{{round($mark->marks/$assessment->max_marks*$assessment->weightage)}}
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
                            $marks_obtained += round($mark->marks / $assessment->max_marks * $assessment->weightage);
                            $total_weightage += $assessment->weightage;
                            $flag = true;
                            ?>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <?php
            $temp = 0.0;
            if ($total_weightage != 0)
                $temp = $marks_obtained / $total_weightage * 100;
            $percentage = $temp;

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
            <div class="panel panel-subject" align="center"
                 style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                <div class="subject-name" style="float:left">
                    Grade: {{$grade}}
                </div>
            </div>
            <script src="{{URL::to('/')}}/js/Chart.js"></script>
            <canvas id="myChart"
                    style="margin-top:2.5%;padding-left: 10%; padding-bottom:2.5%;padding-right: 10%; margin-bottom: 5%"></canvas>
            <script>
                var ctx = document.getElementById("myChart");
                var data = {
                    labels: ["Min", "Average", "Max", "You"],
                    datasets: [
                        {
                            label: "Percentage",
                            hoverBackgroundColor: [
                                'rgba(231, 76, 60, 0.5)',
                                'rgba(241, 196, 15, 0.5)',
                                'rgba(46, 204, 113, 0.5)',
                                'rgba(52, 152, 219, 0.5)'
                            ],
                            backgroundColor: [
                                'rgba(231, 76, 60, 0.25)',
                                'rgba(241, 196, 15, 0.25)',
                                'rgba(46, 204, 113, 0.25)',
                                'rgba(52, 152, 219, 0.25)'
                            ],
                            borderColor: [
                                'rgba(231, 76, 60,.75)',
                                'rgba(241, 196, 15,.75)',
                                'rgba(46, 204, 113, .75)',
                                'rgba(52, 152, 219, .75)'
                            ],
                            hoverBorderColor: [
                                'rgba(231, 76, 60,1)',
                                'rgba(241, 196, 15,1)',
                                'rgba(46, 204, 113, 1)',
                                'rgba(52, 152, 219, 1)'
                            ],
                            hoverBorderWidth: 5,
                            borderWidth: 2,
                            data: [{{round($min,2)}}, {{round($average,2)}}, {{round($max,2)}}, {{round($percentage,2)}}]
                        }
                    ]
                };
                new Chart(ctx, {
                    type: "bar",
                    data: data,
                    options: {
                        scales: {
                            xAxes: [{
                                stacked: true
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    }
                });
            </script>
        </div>
    @endif
@endsection