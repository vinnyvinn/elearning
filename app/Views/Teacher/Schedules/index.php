<?php
$sections = (new \App\Models\Subjectteachers())->groupBy('section_id')->where('teacher_id',$teacher->id)->findAll();
$active = $sections[0];
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Regular Class Schedule</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row mt-3" style="padding-left:1em;padding-right:1em">
                <ul class="nav nav-pills nav-pill-bordered">
                    <?php
                    foreach ($sections as $section):?>
                        <li class="nav-item">
                            <a class="walla nav-link <?php if ($active->section_id == $section->section_id):?>active<?php endif;?>" id="base-pill<?php echo $section->section_id;?>" data-toggle="pill" href="#pill<?php echo $section->section_id;?>" aria-expanded="true" section-id="<?php echo $section->section_id;?>"  class-id="<?php echo $section->class_id;?>" value="0999"><?php echo (new \App\Models\Classes())->find($section->class_id)->name;
                              echo '<br>';
                              echo (new \App\Models\Sections())->find($section->section_id)->name;
                                ?>
                            </a>

                        </li>
                    <?php endforeach;?>

                </ul>
                <div class="tab-content px-1 pt-1">
                    <?php
                    foreach ($sections as $section):?>
                    <div role="tabpanel" class="tab-pane <?php if ($active->section_id == $section->section_id):?>active<?php endif;?>" id="pill<?php echo $section->section_id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $section->section_id;?>">
                        <div id="ajaxContent"></div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    //function getSections(classId) {
    //    var data = {
    //        url: "<?php //echo site_url('ajax/class/') ?>//" + classId + "/sections",
    //        data: "session=" + classId,
    //        loader: true
    //    };
    //    ajaxRequest(data, function (data) {
    //        $('#section_id').html(data);
    //    });
    //}
    var section = "<?php echo $active->section_id?>";
    setTimeout(()=>{
        getRegularSchedule();
    },1000)

    $('.walla').on('click',function (){
        section = $(this).attr('section-id');
        getRegularSchedule();
    })

    function getRegularSchedule() {
        var d = {
            url: "<?php echo site_url(route_to('teacher.schedules.student.get_regular')); ?>",
            loader: true,
            data: "section="+section
        };
        ajaxRequest(d, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>