<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">ASP Class Schedule</h6>
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
        <div class="card-header">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-4">
                    <select class="form-control select2" data-toggle="select2" id="student" onchange="getAspSchedule()"
                            name="student">
                        <?php
                        if (count($parent->students) > 1) {
                            ?>
                            <option value="">-- Select Student --</option>
                            <?php
                        foreach ($parent->students as $student) {
                            ?>
                            <option value="<?php echo $student->section->id; ?>"><?php echo $student->profile->name; ?></option>
                        <?php
                        }
                        ?>
                        <?php
                        } else {
                        ?>
                            <option selected value="<?php echo $parent->students[0]->section->id; ?>"><?php echo $parent->students[0]->profile->name; ?></option>
                            <script>
                                $(document).ready(function () {
                                    getAspSchedule();
                                })
                            </script>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-sm btn-success btn-block" onclick="getAspSchedule()"><i
                            class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="">
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>

<script>
    function getAspSchedule() {
        var section = $('#student').val();

        var d = {
            url: "<?php echo site_url(route_to('parent.schedules.student.get_asp')); ?>",
            loader: true,
            data: "section="+section
        };
        ajaxRequest(d, function (data) {
            $('#ajaxContent').html(data);
        })
    }

</script>