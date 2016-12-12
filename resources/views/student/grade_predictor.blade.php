@extends('layouts.app')

@section('content')
    <div align="center">
        <img src="{{URL::to('/')}}/images/bmu_logo.png" alt="BMU Logo" class="img-responsive" height="150"
             width="150"/>
    </div>
    <style>
        div.margin {
            margin: 0 300px 0;
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
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Grade Predictor</div>
                        <div class="panel-body">
                            <form class="form-horizontal" name="form" id="form">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Desired Grade</label>
                                    <div class="col-md-6">
                                        <label>
                                            <input type="number" class="form-control" required autofocus min="0"
                                                   max="10" step="any" name="grade"
                                                   value="{{isset($_GET['grade'])?$_GET['grade']:''}}">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button class="btn btn-primary" type="submit">
                                            Predict
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $sem_total = 0.0;
        $sem_count = 0.0;
        ?>
        @foreach($subjects as $subject)
            @foreach(json_decode((html_entity_decode($subject->stream, true))) as $stream)
                @if(Auth::user()->stream==$stream)
                    <?php
                    $user_marks = 0.0;
                    $user_total = 0.0;
                    for ($i = 0; $i < count($marks); $i++) {
                        if ($marks[$i]->student_id == Auth::user()->id and $marks[$i]->subject_id == $subject->id) {
                            for ($j = 0; $j < count($assessments); $j++) {
                                if ($assessments[$j]->id == $marks[$i]->assessment_id) {
                                    $user_marks += $marks[$i]->marks / $assessments[$j]->max_marks * $assessments[$j]->weightage;
                                    $user_total += $assessments[$j]->weightage;
                                }
                            }
                        }
                    }
                    $percentage = 0.0;
                    if ($user_total != 0)
                        $percentage = $user_marks / $user_total * 100;
                    ?>
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
                                    $total_marks += $marks[$j]->marks / $assessments[$k]->max_marks * $assessments[$k]->weightage;
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
        @endforeach
        <?php
        $flag = false;
        $sgpa = $sem_total / $sem_count;
        if (isset($_GET['grade'])) {
            $flag = true;
            $grade_required = 2 * $_GET['grade'] - $sgpa;
        }
        ?>
        @if($flag)
            <div class="margin" align="center">
                @if($grade_required>=0 and $grade_required<=10)
                    <div class="alert alert-info">
                        To achieve the desired grade, GPA required next sem is: {{$grade_required}}
                    </div>
                @else
                    <div class="alert alert-danger">
                        Desired grade cannot be achieved next semester :(
                    </div>
                @endif
            </div>
        @endif
    @endif
@endsection