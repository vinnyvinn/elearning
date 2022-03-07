<?php
$sems = getSession()->semesters;
$semester = $sems[0];
$student = $parent->students[0];
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <ul class="nav nav-pills nav-pill-bordered">
                    <?php
                    foreach ($sems as $sem):?>
                        <li class="nav-item">
                            <a class="walla nav-link <?php if ($semester->id == $sem->id):?>active<?php endif;?>" id="base-pill<?php echo $sem->id;?>" data-toggle="pill" href="#pill<?php echo $sem->id;?>" aria-expanded="true" sem-id="<?php echo $sem->id;?>"><?php echo $sem->name;
                                ?></a>
                        </li>
                    <?php endforeach;?>

                </ul>
                <div class="tab-content px-1 pt-1">
                    <?php
                    foreach ($sems as $sem):?>
                        <div role="tabpanel" class="tab-pane <?php if ($semester->id == $sem->id):?>active<?php endif;?>" id="pill<?php echo $sem->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $sem->id;?>">
                            <div class="card">
                                <div>
                                    <div class="ajaxContent"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
            <hr/>
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>
    var semester = "<?php echo $semester->id?>";
    var student = "<?php echo $student->id?>";

    $('.walla').on('click',function (){
        semester = $(this).attr('sem-id');
        getClassWork();
    })

    setTimeout(()=>{
       getClassWork();
    },1000)
    function getClassWork() {
        if(student == '' || semester == '') {
            toast("Error", "Please select both fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('parent.continuous_assessments.view_exam')); ?>",
                loader: true,
                data: "student=" + student + "&semester=" + semester
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>
