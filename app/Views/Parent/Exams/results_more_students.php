<?php
$students = $parent->studentsCurrent;
$student = $parent->studentsCurrent[0];
$exams = $students[0]->getExams();
$exam = $exams[0]->id;

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam Results </h6>
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
    <div>
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
                <div class="tab-content px-1 pt-1">
                    <?php
                    foreach ($students as $student):?>
                        <div role="tabpanel" class="tab-pane <?php if ($active->id == $student->id):?>active<?php endif;?>" id="pill<?php echo $student->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $student->id;?>">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center justify-content-center">
                                        <input type="hidden" name="student" class="std">
                                        <div class="col-md-6">
                                            <select class="form-control select2 exam" data-toggle="select2" id="exam" name="exam">
                                                <?php foreach ($exams as $ex):?>
                                                    <option value="<?php echo $ex->id?>"><?php echo $ex->name;?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-success btn-block" onclick="getRes()"><i
                                                        class="fa fa-filter"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="ajaxContent">

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var exam = "<?php echo $exam?>";
    var student = "<?php echo $student->id?>";
    $('.exam').on('change',function (){
        exam = $(this).val();
    })
    $('.exam').val(exam);
    $('.std').val(student)

    setTimeout(()=>{
        getResults(student,exam);
    },500)

    $('.exam').val(exam);
    $('.std').val(student)

    $('.walla').on('click',function (){
        student = $(this).attr('student-id');
        exam = $('.exam').val();
        getResults(student,exam);
    })
    function getRes(){
        getResults(student,exam);
    }

    var getResults = function (student,exam) {
        if (student != '' && exam != '') {
            var data = {
                url: "<?php echo site_url(route_to('parent.exams.results.get')); ?>",
                data: 'exam=' + exam + '&student=' + student,
                loader: true
            }
            ajaxRequest(data, function (data) {
                $('.ajaxContent').html(data);
            });
        } else {
            toast("Error", "Please select the student and the exam", 'error');
        }
    }
</script>