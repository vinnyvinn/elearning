<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Lesson Plan</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row mt-3 justify-content-center" style="padding-left:1em;padding-right:1em">
                <div class="col-md-2 mb-1">
                    <select name="class" id="class_id" class="form-control form-control-sm select2"
                            data-toggle="select2"
                            onchange="getSections($(this).val())" required>
                        <option value="">Class</option>
                        <?php
                        $school_session = getSession();
                        if($school_session) {
                            $classes = $school_session->classes()->findAll();

                            if($classes && is_array($classes) && count($classes) > 0) {
                                foreach ($classes as $class) {
                                    echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="section" id="section_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Section</option>

                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="section" id="subject_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Subject</option>

                    </select>
                </div>
                <div class="col-md-2">
                    <select name="month" id="month_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Month</option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="week" id="week_id" class="form-control form-control-sm select2" data-toggle="select2"
                            required>
                        <option value="">Week</option>
                        <?php
                        for ($i = 1; $i <= 4; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getLessonPlan()"><i
                            class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>
    function getSections(classId) {
        var data = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
            data: "session=" + classId,
            loader: true
        };
        ajaxRequest(data, function (data) {
            $('#section_id').html(data);
        });

        var d = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
            data: "class=" + classId,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });
    }

    function getLessonPlan() {
        var section = $('#section_id').val();
        var subject = $('#subject_id').val();
        var month = $('#month_id').val();
        var week = $('#week_id').val();
        if(section == '' || subject == '' || month == '' || week == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('admin.academic.get_lesson_plan')); ?>",
                loader: true,
                data: "section=" + section + "&subject=" + subject + "&month=" + month + "&week=" + week
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>