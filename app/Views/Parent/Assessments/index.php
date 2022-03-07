<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Assessments</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('parent_assessments_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row mt-3" style="padding-left:1em;padding-right:1em">
                <div class="col-md-3 mb-1">
                    <select name="class" id="student_id" class="form-control form-control-sm select2"
                            data-toggle="select2" onchange="getStudentSubjects()" required>
                        <option value="">Select a student</option>
                        <?php
                        $students = $parent->students;
                        if($students) {
                            if(count($students) > 1) {
                                foreach ($students as $student) {
                                    echo '<option value="' . $student->id . '">' . $student->profile->name . '</option>';
                                }
                            } else {
                                echo '<option value="' . $students[0]->id . '">' . $students[0]->profile->name . '</option>';
                                echo 'getStudentSubjects()';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="subject" id="subject_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select Subject</option>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="month" id="month_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select Month</option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            ?>
                            <option <?php echo date('n') == $i ? 'selected' : ''; ?>
                                    value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="week" id="week_id" class="form-control form-control-sm select2" data-toggle="select2"
                            required>
                        <option value="">Select Week</option>
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getAssessments()"><i
                                class="fa fa-filter"></i> Filter
                    </button>
                </div>
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
    $('select').on('change', function () {
        $('#ajaxContent').html('');
    });

    function getAssessments() {
        var student = $('#student_id').val(),
            month = $('#month_id').val(),
            week = $('#week_id').val(),
            subject = $('#subject_id').val();
        if (student != '' && subject != '' && month != '' && week != '') {
            var d = {
                data: "student=" + student + "&subject=" + subject + "&month=" + month + "&week=" + week,
                url: "<?php echo site_url(route_to('parent.classes.assessments.get')) ?>",
                loader: true
            };
            ajaxRequest(d, function (data) {
                $('#ajaxContent').html(data);
            })
        } else {
            toast('Error', 'Please select all fields in the dropdowns', 'error');
        }
    }

    function getStudentSubjects() {
        var student = $('#student_id').val();
        var d = {
            url: "<?php echo site_url(route_to('parent.classes.assessments.get_student_subjects_ajax')) ?>",
            data: "student=" + student,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });
    }
</script>