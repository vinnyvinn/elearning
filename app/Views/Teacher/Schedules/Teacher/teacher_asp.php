<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Teacher's ASP Schedule</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row mt-3 justify-content-center" style="padding-left:1em;padding-right:1em">
                <div class="col-md-3 mb-1">
                    <select name="teacher" id="teacher_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select Teacher</option>
                        <?php

                        use App\Models\Teachers;

                        $teachers = (new Teachers())->findAll();
                        if ($teachers && count($teachers) > 0) {
                            foreach ($teachers as $teacher) {
                                ?>
                                <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->profile->name; ?></option>
                                <?php
                            }
                        } else {
                            echo '<option value="">No teachers registered</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getTeachersASPSchedule()"><i
                            class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div id="ajaxData"></div>
    </div>
</div>
<script>
    function getTeachersASPSchedule() {
        var teacher = $('#teacher_id').val();
        if (teacher != '') {
            var e = {
                url: "<?php echo site_url(route_to('teacher.schedules.teacher.get_asp')); ?>",
                data: "teacher=" + teacher,
                loader: true
            };
            ajaxRequest(e, function (data) {
                $('#ajaxData').html(data);
            })
        } else {
            toast("error", "Please select a teacher", 'error');
        }
    }
</script>