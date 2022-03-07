<?php
$student = $parent->studentsCurrent[0];
$section = $student->section->id;
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
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div>
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>

<script>
    var section = "<?php echo $section;?>";
    setTimeout(()=>{
        var d = {
            url: "<?php echo site_url(route_to('parent.schedules.student.get_regular')); ?>",
            loader: true,
            data: "section="+section
        };
        ajaxRequest(d, function (data) {
            $('#ajaxContent').html(data);
        })
    },1000)



</script>