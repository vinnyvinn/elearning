<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Student Attendance</h6><br/>
                    <small class="text-white">Student Attendance</small>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('teachers_attendance_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <form method="post" action="<?php echo site_url(route_to('admin.attendance.saveTeacher')); ?>" class="ajaxForm" loader="true">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input class="form-control form-control-sm datepicker" type="text" id="date" name="date" value="<?php echo date('m/d/Y'); ?>"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="filter" class="btn btn-sm btn-primary btn-block"
                                onclick="getTeachers()">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
            <div id="ajaxContent"></div>
        </form>
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

    var getTeachers = function () {
        var classId = $('#class').val();
        var sectionId = $('#section').val();
        var date = $('#date').val();
        if (classId == '' || sectionId == '' || date == '') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var data = {
                url: "<?php echo site_url(route_to('admin.attendance.teachers.get_ajax')) ?>",
                data: "date=" + date,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxContent').html(data);
            });
        }
    }
</script>