<?php
$students = $parent->studentsCurrent;
$student = $parent->studentsCurrent[0];
$semester = getSession()->semesters[0]->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Continuous Assessment </h6>
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
                $sems = getSession()->semesters;
                foreach ($sems as $sem):?>
                    <li class="nav-item">
                        <a class="sems nav-link <?php if ($semester == $sem->id):?>active<?php endif;?>" id="base-pill<?php echo $sem->id;?>" data-toggle="pill" href="#pill<?php echo $sem->id;?>" aria-expanded="true" sem-id="<?php echo $sem->id;?>"><?php echo $sem->name;
                            ?></a>
                    </li>
                <?php endforeach;?>
            </ul>

            <div class="tab-content px-1 pt-1">
                <?php
                foreach ($students as $student):?>
                    <div role="tabpanel" class="tab-pane <?php if ($active->id == $student->id):?>active<?php endif;?>" id="pill<?php echo $student->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $student->id;?>">

                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <div id="ajaxContent">
            <div class="card-body">
                <div class="alert alert-warning">
                    Use the filter above to fetch data
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var stud = "<?php echo $student->id;?>";
    var sem_ = "<?php echo $semester;?>";

    $('.sems').on('click',function (){
        sem_ = $(this).attr('sem-id');
        getResults(stud,sem_);
    })


    setTimeout(()=>{
        getResults(stud,sem_);
    },500);


    $('.walla').on('click',function (){
        stud = $(this).attr('student-id');
        getResults(stud,sem_);
    })

    function getResults(student,semester) {
    //    console.log(`semester ${semester} stud ${student}`)
        var d = {
            url: "<?php echo site_url(route_to('parent.assessment.results')) ?>",
            data: "student=" + student  + "&semester=" + semester,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#ajaxContent').html(data);
            moveWindow();
        });
    }


</script>