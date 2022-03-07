<?php

use App\Models\Students;
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
                    <h6 class="h2 text-white d-inline-block mb-0">Exam Schedule</h6>
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
    <div class="card" style="margin-bottom: 5%">
        <div class="card-header">
            <h4 class="card-title">Exam Schedule  </h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <ul class="nav nav-pills nav-pill-bordered">
                    <?php
                    $active = $students[0];
                    foreach ($students as $student):?>
                        <li class="nav-item">
                            <a class="walla nav-link <?php if ($active->id == $student->id):?>active<?php endif;?>" id="base-pill<?php echo $student->id;?>" data-toggle="pill" href="#pill<?php echo $student->id;?>" aria-expanded="true" student-id="<?php echo $student->section->id;?>"><?php echo $student->profile->name;
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
                            <a class="exams nav-link <?php if ($exam == $exm->id):?>active<?php endif;?>" id="base-pill<?php echo $exm->id;?>" data-toggle="pill" href="#pill<?php echo $exm->id;?>" aria-expanded="true" exam-id="<?php echo $exm->id;?>"><?php echo $exm->name;
                                ?></a>
                        </li>
                    <?php endforeach;?>

                </ul>

                <div class="tab-content px-1 pt-1">
                    <?php
                    foreach ($students as $student):?>
                        <div role="tabpanel" class="tab-pane <?php if ($active->id == $student->id):?>active<?php endif;?>" id="pill<?php echo $student->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $student->id;?>">
                            <div class="card">
                              <div>
                             <div class="ajaxContent"></div>
                             </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var student = "<?php echo $student->id;?>";
    var exam = "<?php echo $exam;?>";

       setTimeout(()=>{
                  exam = "<?php echo $exam;?>"
                  student = "<?php echo $student->id;?>";
                  $('.exam').val(exam);
                  getSchedule(exam,student);
              },500)


          $('.walla').on('click',function (){
            student = $(this).attr('student-id');
            getSchedule(exam,student);
          })

        $('.exams').on('click',function (){
            exam = $(this).attr('exam-id');
            getSchedule(exam,student)
        })

    var getSchedule = function (exam,student) {
           console.log('ex -> '+exam+' std-> '+student)
            if (exam !== '' && student !== '') {
                var d = {
                    url: "<?php echo site_url(route_to('parent.exams.schedule')) ?>",
                    data: "student=" + student + "&exam=" + exam,
                    loader: true
                }
                ajaxRequest(d, function (data) {
                    $('.ajaxContent').html(data);
                })
            } else {
                toast("Error", "Please select the student and the exam", 'error');
            }


    }
</script>