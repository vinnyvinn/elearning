<?php

use App\Models\Sessions;

if($students && count($students) > 0) {
    ?>
    <form method="post" action="<?php echo site_url(route_to('admin.students.depart.students')); ?>" class="ajaxForm" loader="true">
        <div class="row">
            <div class="col-md-6" style="max-width: 16%;margin-left: 1%">
        <div class="form-group">
            <label>Count</label>
            <input type="checkbox" name="cool_count" value="1" id="counter">
        </div>
            </div>
            <div class="col-md-6" style="max-width: 16%;margin-left: -5%">
        <div class="form-group sem" style="display: none">
            <select name="semester" id="semester" class="form-control">
                <?php foreach (getSession()->semesters as $semester):?>
                    <option value="<?php echo $semester->id?>"><?php echo $semester->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        </div>
        </div>
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
            <div class="row">
                    <button type="submit" id="filter" class="btn btn-primary" style="margin-left: 10%">
                    <i class="fa fa-chart-bar"></i> Depart
                    </button>
            </div>
        </div>
    </form>
    <script>
        $('#counter').on('click',function (){
            if ($(this).is(':checked')){
                $('.sem').show();
            }else {
                $('.sem').hide();
            }
        })
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
