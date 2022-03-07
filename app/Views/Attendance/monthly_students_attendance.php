<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Student Attendance</h6><br/>
                    <small class="text-white">Student Attendance</small>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a class="btn btn-sm btn-success" href="<?php echo site_url(route_to('admin.attendance.record_students')); ?>"><i
                                class="fa fa-plus"></i> Record Attendance
                    </a>
                    <?php do_action('student_attendance_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center">

                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control select2" data-toggle="select2" id="class" onchange="getSections()">
                            <option value=""> -- Grade --</option>
                            <?php
                            $classes = getSession()->classes->findAll();
                            if ($classes && count($classes) > 0) {
                                foreach ($classes as $class) {
                                    ?>
                                    <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control select2" data-toggle="select2" id="section">
                            <option value=""> -- Section --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control select2" data-toggle="select2" id="month">
                            <option value=""> -- Month --</option>
                            <option <?php echo date('m') == '01' ? 'selected' : ''; ?> value='01'>January</option>
                            <option <?php echo date('m') == '02' ? 'selected' : ''; ?> value='02'>February</option>
                            <option <?php echo date('m') == '03' ? 'selected' : ''; ?> value='03'>March</option>
                            <option <?php echo date('m') == '04' ? 'selected' : ''; ?> value='04'>April</option>
                            <option <?php echo date('m') == '05' ? 'selected' : ''; ?> value='05'>May</option>
                            <option <?php echo date('m') == '06' ? 'selected' : ''; ?> value='06'>June</option>
                            <option <?php echo date('m') == '07' ? 'selected' : ''; ?> value='07'>July</option>
                            <option <?php echo date('m') == '08' ? 'selected' : ''; ?> value='08'>August</option>
                            <option <?php echo date('m') == '09' ? 'selected' : ''; ?> value='09'>September</option>
                            <option <?php echo date('m') == '10' ? 'selected' : ''; ?> value='10'>October</option>
                            <option <?php echo date('m') == '11' ? 'selected' : ''; ?> value='11'>November</option>
                            <option <?php echo date('m') == '12' ? 'selected' : ''; ?> value='12'>December</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" id="filter" class="btn btn-sm btn-primary btn-block" onclick="getStudents()">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div id="ajaxContent"></div>
    </div>
</div>
<script>
    var getSections = function () {
        var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section').html(data);
            });
        }
    };

    var getStudents = function () {
        var year = '<?php echo date('Y');?>';
        var month = $('#month').val();
        var classId = $('#class').val();
        var sectionId = $('#section').val();
        if (classId == '' || sectionId == '') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/attendance/students-monthly/') ?>" + classId + "/section/" + sectionId + "/" + month + "/" + year,
                data: "month=" + month + "&class=" + classId + "&section=" + sectionId + "&year=" + year,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxContent').html(data);
            });
        }
    }
</script>