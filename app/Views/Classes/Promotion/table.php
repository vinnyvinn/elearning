<?php

use App\Models\Sessions;

if($students && count($students) > 0) {
    ?>
    <form method="post" action="<?php echo site_url(route_to('admin.promotion.promote')); ?>" class="ajaxForm" loader="true">
        <div class="table-responsive">
            <table class="table datatable" id="datatable-basic">
                <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" id="checkAllStudents" /></th>
                    <th>Admission #</th>
                    <th>Name</th>
                    <th>Current Class</th>
                    <th>Current Section</th>
                    <th>Session</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($students as $student) {
                    ?>
                    <input type="hidden" name="oldSession" value="<?php echo $student->session->id; ?>" />
                    <input type="hidden" name="oldClass" value="<?php echo $student->class->id; ?>" />
                    <input type="hidden" name="oldSection" value="<?php echo $student->section->id; ?>" />
                    <tr>
                        <td><input type="checkbox" id="studentCheck" name="student[]" value="<?php echo $student->id; ?>" /></td>
                        <td><?php echo $student->admission_number; ?></td>
                        <td><?php echo $student->profile->name; ?></td>
                        <td><?php echo $student->class->name; ?></td>
                        <td><?php echo $student->section->name; ?></td>
                        <td><?php echo $student->session->name; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <h2 class="h3">Select a new class for the selected students</h2>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control select2" name="newSession" id="newSession" onchange="getNewClass()">
                            <option value=""> -- Select session --</option>
                            <?php
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
                        <select class="form-control select2" name="newClass" id="newClass" onchange="getNewSections()">
                            <option value=""> -- Select class--</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control select2" name="newSection" id="newSection">
                            <option value=""> -- Select section --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" id="filter" class="btn btn-primary btn-block"">
                    <i class="fa fa-chart-bar"></i> Promote
                    </button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $("#checkAllStudents").on('change', function (e) {
            //$("#studentCheck").each(function () { this.checked = !this.checked; })
            //$("#studentCheck").prop('checked', $(this).prop("checked"));
            //$("input:checkbox").prop('checked', $(this).prop("checked"));
            $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
        });
        var getNewClass = function () {
            var session = $('#newSession').val();
            if (session == '') {
                toast('Error', 'Please select a Session', 'error');
            } else {
                var data = {
                    url: "<?php echo site_url('ajax/session/') ?>" + session + "/classes",
                    data: "session=" + session,
                    loader: true
                };
                ajaxRequest(data, function (data) {
                    $('#newClass').html(data);
                });
            }
        };

        var getNewSections = function () {
            var classId = $('#newClass').val();
            if (classId == '') {
                toast('Error', 'Please select a class', 'error');
            } else {
                var data = {
                    url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                    data: "session=" + classId,
                    loader: true
                };
                ajaxRequest(data, function (data) {
                    $('#newSection').html(data);
                });
            }
        };
    </script>
    <?php
} else {
    ?>
    <div class="card-body">
        <div class="alert alert-danger">
            No students were found for this class section
        </div>
    </div>
    <?php
}
