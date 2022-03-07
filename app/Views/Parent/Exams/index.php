<?php
$student = $parent->studentsCurrent[0];
$exams = $student->getExams();
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
            <h4 class="card-title">Exam Schedule</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <ul class="nav nav-pills nav-pill-bordered">
                    <?php
                    foreach ($exams as $exm):?>
                        <li class="nav-item">
                            <a class="walla nav-link <?php if ($exam == $exm->id):?>active<?php endif;?>" id="base-pill<?php echo $exm->id;?>" data-toggle="pill" href="#pill<?php echo $exm->id;?>" aria-expanded="true" exam-id="<?php echo $exm->id;?>"><?php echo $exm->name;
                                ?></a>
                        </li>
                    <?php endforeach;?>

                </ul>
                <div class="tab-content px-1 pt-1">
                    <?php
                    foreach ($exams as $exm):?>
                        <div role="tabpanel" class="tab-pane <?php if ($exam == $exm->id):?>active<?php endif;?>" id="pill<?php echo $exm->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $exm->id;?>">
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

    $('.walla').on('click',function (){
        exam = $(this).attr('exam-id');
        getSchedule(exam,student);
    })

    var getExams = function () {
        if (student != '') {
            var data = {
                url: "<?php echo site_url(route_to('parent.exams.ajax_exams')); ?>",
                data: 'student=' + student,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('.exam').html(data);
            });
        } else {
            toast("Error", "Please select a student", 'error');
        }
    };

              var exam = "<?php echo $exam;?>"
              var student = "<?php echo $student->id;?>";
              $('.exam').val(exam);
              setTimeout(()=>{

                  var d = {
                      url: "<?php echo site_url(route_to('parent.exams.schedule')) ?>",
                      data: "student=" + student + "&exam=" + exam,
                      loader: true
                  }
                  ajaxRequest(d, function (data) {
                      console.log('cool')
                      $('.ajaxContent').html(data);
                  })
              },1000)


        var getSchedule = function () {
            if (exam != '' && student != '') {
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