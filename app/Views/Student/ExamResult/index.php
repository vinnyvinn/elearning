<?php
$exams = $student->getExams();
$exam = $exams[0];
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
                    do_action('parent_quick_action_buttons', $student); ?>
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

        </div>
        <div class="card-body">
            <ul class="nav nav-pills nav-pill-bordered">
                <?php
                foreach ($exams as $exm):?>
                    <li class="nav-item">
                        <a class="walla nav-link <?php if ($exam->id == $exm->id):?>active<?php endif;?>" id="base-pill<?php echo $exm->id;?>" data-toggle="pill" href="#pill<?php echo $exm->id;?>" aria-expanded="true" exam-id="<?php echo $exm->id;?>"><?php echo $exm->name;
                            ?></a>
                    </li>
                <?php endforeach;?>

            </ul>
            <div class="tab-content px-1 pt-1">
                <?php
                foreach ($exams as $exm):?>
                    <div role="tabpanel" class="tab-pane <?php if ($exam->id == $exm->id):?>active<?php endif;?>" id="pill<?php echo $exm->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $exm->id;?>">
                        <div class="card">
                            <div>
                                <div class="ajaxContent"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <script>
            var marks = [];
            var labels = [];
        </script>
        <div id="ajaxContent">
            <div class="chart">
                <!-- Chart wrapper -->
                <canvas id="chart-bars" class="chart-canvas"></canvas>
            </div>
        </div>
        <div class="card-body">
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
                    <a href="<?php echo site_url(route_to('student.exam.results')); ?>" class="btn btn-sm btn-block btn-default">View Results</a>
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
    var student = "<?php echo $student->id?>";
    var student_name = "<?php echo $student->profile->name?>";
    var exam_name = "<?php echo $exam->name?>";
    var student_class = "<?php echo $student->class->name.', '.$student->section->name;?>";
    var student_id = "<?php echo $student->admission_number;?>";
    var students_no = "<?php echo count($student->section->students);?>"

    setTimeout(()=>{
        getResults(student,exam);
    },1000)

    $("#student_name").html(student_name);
    $("#student_class").html(student_class);
    $("#student_id").html(student_id);
    $("#student_exam").html(exam_name);


    $('.walla').on('click',function (){
        exam = $(this).attr('exam-id');
        getResults(student,exam);

        getExam(exam)
    })

    var getExam = function (exam_id) {
        if (exam_id !== '') {
            var data = {
                url: "<?php echo site_url(route_to('student.exams.get_exam')); ?>",
                data: 'exam=' + exam_id,
                loader: true
            }

            ajaxRequest(data, function (data){
                $("#student_exam").html(JSON.parse(data));
            });
        } else {
            toast("Error", "Please select  the exam", 'error');
        }
    }

    var getResults = function (student,exam) {
        if (student !== '' && exam !== '') {
            var data = {
                url: "<?php echo site_url(route_to('student.exams.ajax_results')); ?>",
                data: 'exam=' + exam + '&student=' + student,
                loader: true
            }
            ajaxRequest(data, renderData);
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
            $('#student_average').html(json.average.toFixed(2));
            $('#student_total').html(json.total_marks);
            $('#student_rank').html(json.rank+'/'+students_no);

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
</script>