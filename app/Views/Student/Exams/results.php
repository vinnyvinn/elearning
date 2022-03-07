<?php
$exams = $student->getExams();
$exam = $exams[0]->id;

//var_dump($exams);

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
                    do_action('parent_quick_action_buttons', $student); ?>
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
    var exam = "<?php echo $exam?>";
    var student = "<?php echo $student->id?>";
    setTimeout(()=>{
        getResults(student,exam);
    },1000)

    $('.walla').on('click',function (){
        exam = $(this).attr('exam-id');
        getResults(student,exam);
    })

    var getResults = function (student,exam) {
        if (student != '' && exam != '') {

            var data = {
                url: "<?php echo site_url(route_to('student.exams.results.get')); ?>",
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