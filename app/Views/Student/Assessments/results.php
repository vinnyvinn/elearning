<?php
$semester = getSession()->semesters[0]->id;

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Assessment Results (<?php echo $student->profile->name?>)</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('parent_assessment_results_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills nav-pill-bordered">
                <?php
                $sems = getSession()->semesters;
                foreach ($sems as $sem):?>
                    <li class="nav-item">
                        <a class="walla nav-link <?php if ($semester == $sem->id):?>active<?php endif;?>" id="base-pill<?php echo $sem->id;?>" data-toggle="pill" href="#pill<?php echo $sem->id;?>" aria-expanded="true" sem-id="<?php echo $sem->id;?>"><?php echo $sem->name;
                            ?></a>
                    </li>
                <?php endforeach;?>

            </ul>
            <div class="tab-content px-1 pt-1">
                <?php
                foreach ($sems as $sem):?>
                    <div role="tabpanel" class="tab-pane <?php if ($semester == $sem->id):?>active<?php endif;?>" id="pill<?php echo $sem->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $sem->id;?>">
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
<script>
    var stud = "<?php echo $student->id;?>";
    var sem_ = "<?php echo $semester;?>";


    $('.walla').on('click',function (){
        sem_ = $(this).attr('sem-id');
        getResults(stud,sem_);
    })


    setTimeout(()=>{
        getResults(stud,sem_);
    },500);

    function getResults(student,semester) {
        var d = {
            url: "<?php echo site_url(route_to('student.assessment.results')) ?>",
            data: "student=" + student + "&semester=" + semester,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('.ajaxContent').html(data);
        });
    }
</script>