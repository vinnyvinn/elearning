<?php
$students = $parent->students;
$student = $parent->students[0];
$exams = $students[0]->getExams();
$exam = $exams[0];

(new \App\Models\Parents());
(new \App\Models\Students());
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam Result</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">

    <div class="card">
        <div class="card-header">
            <h2>Exam Results</h2>
        </div>

        <div class="card-body">
            <ul class="nav nav-pills nav-pill-bordered">
                <?php
                $active = $students[0];
                foreach ($students as $student):?>
                    <li class="nav-item">
                        <a class="walla nav-link <?php if ($active->id == $student->id):?>active<?php endif;?>" id="base-pill<?php echo $student->id;?>" data-toggle="pill" href="#pill<?php echo $student->id;?>" aria-expanded="true" student-id="<?php echo $student->id;?>"><?php echo $student->profile->name;
                            echo '<br>';
                            echo $student->class->name;
                            echo '<br>';
                            echo $student->admission_number;
                            ?></a>
                    </li>
                <?php endforeach;?>

            </ul>
            <br>
            <ul class="nav nav-pills nav-pill-bordered">
                <?php
                foreach ($exams as $exm):?>
                    <li class="nav-item">
                        <a class="exams nav-link <?php if ($exam->id == $exm->id):?>active<?php endif;?>" id="base-pill<?php echo $exm->id;?>" data-toggle="pill" href="#pill<?php echo $exm->id;?>" aria-expanded="true" exam-id="<?php echo $exm->id;?>"><?php echo $exm->name;
                            ?></a>
                    </li>
                <?php endforeach;?>

            </ul>
            <div class="tab-content px-1 pt-1">
                <?php
                foreach ($students as $student):?>
                    <div role="tabpanel" class="tab-pane <?php if ($active->id == $student->id):?>active<?php endif;?>" id="pill<?php echo $student->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $student->id;?>">
                        <div class="card">

                        </div>
                    </div>
                <?php endforeach;?>
            </div>

        </div>
        <script>
          var marks = [];
          var labels = [];
        </script>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card bg-gradient-primary">
                    <div class="card-body">
                        <div class="mb-2">
                            <h1 class="text-white text-center" id="student_exam"></h1>
                            <h1 class="text-white text-center" id="student_name"></h1>
                            <h1 class="text-white text-center" id="student_id"></h1>
                            <h1 class="text-white text-center" id="student_class"></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ajaxContent">
            <div class="chart">
                <!-- Chart wrapper -->
                <canvas id="chart-bars" class="chart-canvas"></canvas>
            </div>
        </div>
        <div class="card-body" id="exams_">
            <div class="row justify-content-center">
                <?php
                //foreach ($parent->students as $student) {
                ?>
                <div class="col-md-4">
                    <div class="card bg-gradient-primary">
                        <div class="card-body">
                            <div class="mb-2">
                                <h1 class="text-white text-center">Total: <span class="text-center text-white"
                                                                                id="student_total">-</span></h1>
                                <h1 class="text-white text-center">Average: <span class="text-center text-white"
                                                                                  id="student_average">-</span></h1>
                                <h1 class="text-white text-center">Rank: <span class="text-center text-white"
                                                                               id="student_rank">-</span></h1>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo site_url(route_to('parent.exams.results')); ?>" class="btn btn-sm btn-block btn-default">View Results</a>
                </div>
                <?php
                //}
                ?>
            </div>
        </div>
    </div>
</div>



<script>
    var exam = "<?php echo $exam->id?>";
    var student = "<?php echo $active->id?>";
    var student_name = "<?php echo $active->profile->name;?>";
    var exam_name = "<?php echo $exam->name?>";
    var student_class = "<?php echo $active->class->name.', '.$active->section->name;?>";
    var student_id = "<?php echo $active->admission_number;?>";
    var students_no = "<?php echo count($active->section->students);?>"
    var rank;

    setTimeout(()=>{
        getResults(student,exam);
    },1000)

    $("#student_name").html(student_name);
    $("#student_class").html(student_class);
    $("#student_id").html(student_id);
    $("#student_exam").html(exam_name);

    $('.exams').on('click',function (){
        exam = $(this).attr('exam-id');
        getResults(student,exam)

        getExam(exam)
    })

    var getExam = function (exam_id) {
        if (exam_id !== '') {
            var data = {
                url: "<?php echo site_url(route_to('parent.exams.ajax_exam')); ?>",
                data: 'exam=' + exam_id ,
                loader: true
            }

            ajaxRequest(data, function (data){
                $("#student_exam").html(JSON.parse(data));
            });
        } else {
            toast("Error", "Please select  the exam", 'error');
        }
    }

    var getStudent = function (student_id) {
        if (student_id !== '') {
            var data = {
                url: "<?php echo site_url(route_to('parent.exams.ajax_student')); ?>",
                data: 'student=' + student_id ,
                loader: true
            }

            ajaxRequest(data, function (data){
                var data = JSON.parse(data);
                $("#student_name").html(data.name);
                $('#student_rank').html(rank+'/'+data.number);
                $("#student_class").html(data.class);
                $("#student_id").html(data.admission);
            });
        } else {
            toast("Error", "Please select  the student", 'error');
        }
    }

    $('.walla').on('click',function (){
        student = $(this).attr('student-id');
        getResults(student,exam);
        getStudent(student);
    })

    var getResults = function (student,exam) {
        if (student !== '' && exam !== '') {
            var data = {
                url: "<?php echo site_url(route_to('parent.exams.ajax_results')); ?>",
                data: 'exam=' + exam + '&student=' + student,
                loader: true
            }
            ajaxRequest(data, renderData);
            moveWindow()
        } else {
            toast("Error", "Please select the student and the exam", 'error');
        }
    }

    var a;
    $(document).ready(function () {
        initExamChart();
    })

    var renderData = function (data) {
        var json = data;
        if (json.marks && json.labels) {
            marks = json.marks;
            labels = json.labels;
            rank = json.rank;

            $('#student_average').html(json.average.toFixed(2));
            $('#student_total').html(json.total_marks);
            $('#student_rank').html(rank+'/'+students_no);

            if (a) {
                a.destroy();
                initExamChart();
            }
        } else {
            notify("Error", data, 'error', 'Close');
        }
    }

    var initExamChart = function () {
        var e = $("#chart-bars");
        e.length && function (e) {
            a = new Chart(e, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{label: "Marks", data: marks}]
                }
            });
            e.data("chart", a);
        }(e)
    }

    $(function (){
        $('.walla').on('click',function (){
            moveWindow();
        })

    })
    function moveWindow(){
        const e = document.getElementById('exams_');
        e.scrollIntoView();
    }
</script>