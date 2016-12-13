@extends('layouts.app')

@section('content')
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

        ?>
        <div align="center">
            <div class="panel panel-subject" align="center"
                 style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                <div class="subject-name">
                    {{Auth::user()->name}}
                </div>
                <div class="teacher-name">
                    {{Auth::user()->batch}} Batch
                    <br>
                    @if(Auth::user()->sem==1)
                        {{Auth::user()->sem}}st
                    @elseif(Auth::user()->sem==2)
                        {{Auth::user()->sem}}nd
                    @elseif(Auth::user()->sem==3)
                        {{Auth::user()->sem}}rd
                    @else
                        {{Auth::user()->sem}}th
                    @endif
                    Semester
                    <br>
                    {{strtoupper(Auth::user()->discipline)}} {{strtoupper(Auth::user()->stream)}}
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
                            <th>Course</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                            <th>Credits</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sem_total = 0.0;
                        $sem_count = 0.0;
                        ?>
                        @foreach($subjects as $subject)
                            <tr class="odd gradeX">
                                @foreach(json_decode((html_entity_decode($subject->stream, true))) as $stream)
                                    @if(Auth::user()->stream==$stream)
                                        <td>{{$subject->subject_name}}</td>
                                        <?php
                                        $user_marks = 0.0;
                                        $user_total = 0.0;
                                        for ($i = 0; $i < count($marks); $i++) {
                                            if ($marks[$i]->student_id == Auth::user()->id and $marks[$i]->subject_id == $subject->id) {
                                                for ($j = 0; $j < count($assessments); $j++) {
                                                    if ($assessments[$j]->id == $marks[$i]->assessment_id) {
                                                        $user_marks += round($marks[$i]->marks / $assessments[$j]->max_marks * $assessments[$j]->weightage);
                                                        $user_total += $assessments[$j]->weightage;
                                                    }
                                                }
                                            }
                                        }
                                        $percentage = 0.0;
                                        if ($user_total != 0)
                                            $percentage = $user_marks / $user_total * 100;
                                        ?>
                                        <td>{{round($percentage,2)}}%</td>
                                    @endif
                                @endforeach
                                <?php
                                $percentage_array = [];
                                for ($i = 0; $i < count($students); $i++) {
                                    $streams = json_decode((html_entity_decode($subject->stream, true)));
                                    for ($x = 0; $x < count($streams); $x++) {
                                        if ($students[$i]->stream == $streams[$x]) {
                                            $total_marks = 0.0;
                                            $total_weightage = 0.0;
                                            $percentage_woosh = 0.0;
                                            for ($j = 0; $j < count($marks); $j++)
                                                for ($k = 0; $k < count($assessments); $k++)
                                                    if ($marks[$j]->student_id == $students[$i]->id and $marks[$j]->assessment_id == $assessments[$k]->id and $marks[$j]->subject_id == $subject->id) {
                                                        $total_weightage += $assessments[$k]->weightage;
                                                        $total_marks += round($marks[$j]->marks / $assessments[$k]->max_marks * $assessments[$k]->weightage);
                                                    }
                                            if ($total_weightage != 0)
                                                $percentage_woosh = $total_marks / $total_weightage * 100;
                                            array_push($percentage_array, $percentage_woosh);
                                        }
                                    }
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

                                $grade = "";
                                if ($percentage >= $mean + 1.5 * $stdev)
                                    $grade = "A+";
                                else if ($mean + 1.5 * $stdev > $percentage and $percentage >= $mean + 0.5 * $stdev)
                                    $grade = "A";
                                else if ($mean + 0.5 * $stdev > $percentage and $percentage >= $mean)
                                    $grade = "B+";
                                else if ($mean > $percentage and $percentage >= $mean - 0.5 * $stdev)
                                    $grade = "B";
                                else if ($mean - 0.5 * $stdev > $percentage and $percentage >= $mean - $stdev)
                                    $grade = "C";
                                else if ($percentage)
                                    $grade = "D";
                                if ($percentage < 40)
                                    $grade = "F";

                                if ($grade == "A+") {
                                    $sem_total += 10;
                                    $sem_count += 1;
                                }
                                if ($grade == "A") {
                                    $sem_total += 9;
                                    $sem_count += 1;
                                }
                                if ($grade == "B+") {
                                    $sem_total += 8;
                                    $sem_count += 1;
                                }
                                if ($grade == "B") {
                                    $sem_total += 7;
                                    $sem_count += 1;
                                }
                                if ($grade == "C") {
                                    $sem_total += 6;
                                    $sem_count += 1;
                                }
                                if ($grade == "D") {
                                    $sem_total += 5;
                                    $sem_count += 1;
                                }
                                if ($grade == "F") {
                                    $sem_count += 1;
                                }
                                ?>
                                <td>{{$grade}}</td>
                                <td>{{$subject->credits}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container">
            </div>
            <div class="panel panel-subject" align="center"
                 style="background-color: {{$color_array[$random_color]}}; border-color: {{$color_array[$random_color]}}">
                <div class="subject-name">
                    Semester GPA: {{$sem_total/$sem_count}}
                </div>
            </div>
        </div>
    @endif
@endsection