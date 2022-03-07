<?php
$students = $parent->studentsCurrent;
$section = $students[0]->section->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Regular Class Schedule</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_schedules_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card" style="margin-bottom: 5%">
        <div class="card-header">
            <h4 class="card-title">Regular Class Schedule</h4>
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
    setTimeout(()=>{
    getSchedule("<?php echo $section;?>");
    },1000)

$('.walla').on('click',function (){
    getSchedule($(this).attr('student-id'));
})

   function getSchedule(section){
           var d = {
               url: "<?php echo site_url(route_to('parent.schedules.student.get_regular')); ?>",
               loader: true,
               data: "section="+section
           };
           ajaxRequest(d, function (data) {
               $('.ajaxContent').html(data);
           })
   }

</script>