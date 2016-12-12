@extends('layouts.app')

@section('content')
    <style>
        div.margin {
            margin: 0 300px 0;
        }
    </style>
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
        <?php
        $color_array = array('#16a085', '#27ae60', '#2980b9');
        $random_color = rand(0, count($color_array) - 1);

        $count_Aplus = 0;
        $count_A = 0;
        $count_Bplus = 0;
        $count_B = 0;
        $count_C = 0;
        $count_D = 0;
        $count_F = 0;

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
                        $total_weightage += $assessments[$k]->weightage;
                        $total_marks += $marks[$j]->marks / $assessments[$k]->max_marks * $assessments[$k]->weightage;
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
            <div class="col-md-12" style="margin-left: 12.5%;width: 75%;margin-top:5%;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr style="background-color: {{$color_array[$random_color]}};color: #fff">
                            <th>Name</th>
                            @foreach($assessments as $assessment)
                                <th>{{$assessment->assessment_name}}</th>
                            @endforeach
                            <th>Total</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                        </tr>
                        </thead>
                        @foreach($students as $student)
                            <tbody>
                            <tr class="odd gradeX">
                                <td>{{$student->name}}</td>
                                <?php
                                $percentage = 0.0;
                                $total_weightage = 0.0;
                                $total_marks = 0.0;
                                ?>
                                @foreach($marks as $mark)
                                    @foreach($assessments as $assessment)
                                        @if ($mark->student_id == $student->id and $mark->assessment_id == $assessment->id)
                                            <?php
                                            $current_marks = $mark->marks / $assessment->max_marks * $assessment->weightage;
                                            $current_weightage = $assessment->weightage;
                                            $total_marks += $mark->marks / $assessment->max_marks * $assessment->weightage;
                                            $total_weightage += $assessment->weightage;
                                            ?>
                                            <td>{{$current_marks}}/{{$current_weightage}}</td>
                                        @endif
                                    @endforeach
                                @endforeach
                                <td>{{$total_marks}}/{{$total_weightage}}</td>
                                <?php
                                if ($total_weightage != 0)
                                    $percentage = $total_marks / $total_weightage * 100;
                                ?>
                                <td>{{round($percentage,2)}}%</td>
                                <?php
                                $grade = "";
                                if ($percentage >= $mean + 1.5 * $stdev) {
                                    $grade = "A+";
                                    $count_Aplus++;
                                } else if ($mean + 1.5 * $stdev > $percentage and $percentage >= $mean + 0.5 * $stdev) {
                                    $grade = "A";
                                    $count_A++;
                                } else if ($mean + 0.5 * $stdev > $percentage and $percentage >= $mean) {
                                    $grade = "B+";
                                    $count_Bplus++;
                                } else if ($mean > $percentage and $percentage >= $mean - 0.5 * $stdev) {
                                    $grade = "B";
                                    $count_B++;
                                } else if ($mean - 0.5 * $stdev > $percentage and $percentage >= $mean - $stdev) {
                                    $grade = "C";
                                    $count_C++;
                                } else if ($percentage < $mean - $stdev and $percentage >= 40) {
                                    $grade = "D";
                                    $count_D++;
                                }
                                if ($percentage < 40) {
                                    $grade = "F";
                                    $count_F++;
                                }
                                ?>
                                <td>{{$grade}}</td>
                            </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="col-md-12" style="margin-left: 25%;width: 50%;margin-top:5%;margin-bottom: 5%;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr style="background-color: {{$color_array[$random_color]}};color: #fff">
                            <th>Grade</th>
                            <th>Frequency</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="odd gradeX">
                            <td>A+</td>
                            <td>{{$count_Aplus}}</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>A</td>
                            <td>{{$count_A}}</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>B+</td>
                            <td>{{$count_Bplus}}</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>B</td>
                            <td>{{$count_B}}</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>C</td>
                            <td>{{$count_C}}</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>D</td>
                            <td>{{$count_D}}</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>F</td>
                            <td>{{$count_F}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <?php
                $hex = $color_array[$random_color];
                list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
                ?>
                <script src="{{URL::to('/')}}/js/Chart.js"></script>
                <canvas id="myChart"
                        style="padding-left: 10%; padding-bottom:10%;padding-right: 10%; margin-bottom: 5%"></canvas>
                <script>
                    var ctx = document.getElementById("myChart");
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["A+", "A", "B+", "B", "C", "D", "F"],
                            datasets: [{
                                pointHoverBorderWidth: 2,
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 10,
                                pointRadius: 5,
                                label: 'Frequency of Grades',
                                data: [
                                    {{$count_Aplus}},
                                    {{$count_A}},
                                    {{$count_Bplus}},
                                    {{$count_B}},
                                    {{$count_C}},
                                    {{$count_D}},
                                    {{$count_F}}],
                                backgroundColor: "rgba({{$r}},{{$g}},{{$b}},0.25)",
                                borderColor: "rgba({{$r}},{{$g}},{{$b}},0.75)",
                                pointBorderColor: "rgba({{$r}},{{$g}},{{$b}},1)",
                                pointHoverBackgroundColor: "rgba({{$r}},{{$g}},{{$b}},1)",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                borderWidth: 2.5
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>
            </div>
        </div>
    @endif
@endsection