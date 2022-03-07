<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Accounting</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('admin_accounting_collect_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header pb-0 mb--1">
            <h4 class="h2">Record Fee Collection</h4>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="session" onchange="getClass()">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="class" onchange="getSections()">
                            <option value=""> -- Select class --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="section">
                            <option value=""> -- Select section --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" id="filter" class="btn btn-primary btn-block" onclick="getStudents()">
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
        var classId = $('#class').val();
        var sectionId = $('#section').val();
        if (classId == '' || session == '' || sectionId == '') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/collect/') ?>" + session + "/class/" + classId + "/section/" + sectionId,
                data: "session=" + session + "&class=" + classId + "&section=" + sectionId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxData').html(data);
            });
        }
    }
</script>