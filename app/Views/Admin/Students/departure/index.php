<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Departures</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.students.departing')); ?>" class="btn btn-sm btn-success"><i
                                class="fa fa-user-plus"></i> New Departure</a>
                    <?php do_action('students_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header pb-0 mb--1">
            <h4 class="h2">Departures</h4>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control select2" id="session">
                            <option value=""> -- Select session --</option>
                            <?php
                            use App\Models\Sessions;
                            $ss = (new Sessions())->orderBy('id', 'DESC')->findAll();
                            if ($ss && count($ss) > 0) {
                                foreach ($ss as $s) {
                                    ?>
                                    <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control select2" id="class" required>
                            <option value=""> -- Select Class --</option>
                            <option value="all"> view all </option>
                            <?php
                            use App\Models\Classes;
                            $classes = (new Classes())->orderBy('id', 'DESC')->where('session',active_session())->findAll();
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
                <div class="col-md-4">
                    <button type="button" id="filter" class="btn btn-primary btn-sm btn-block" onclick="getStudents()">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div id="ajaxData"></div>
    </div>
</div>
<script>
    var getClass = function () {
        var session = $('#session').val();
        if (session == '') {
            toast('Error', 'Please select a Session', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/session/') ?>" + session + "/classes",
                data: "session=" + session,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#class').html(data);
            });
        }
    };

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
        var session = $('#session').val();
        var class_ = $('#class').val();
        if (session == '' || class_ =='') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/students/departures/') ?>" + session,
                data: "session=" + session+"&class="+class_,
                loader: true
            };
            ajaxRequest(data, function (data) {
                console.log(data)
                $('#ajaxData').html(data);
            });
        }
    }
</script>