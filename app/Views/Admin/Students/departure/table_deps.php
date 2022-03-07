<?php
use App\Models\Sessions;
if($students && count($students) > 0) {
    ?>
        <div class="container-fluid">
     <div class="row">

        <div class="table-responsive">
            <table class="table datatable" id="students-table">
                <thead class="thead-light">
                <tr>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Session</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($students as $student) {
                    ?>
                    <tr>
                        <td><?php echo $student->profile->name; ?></td>
                        <td><?php echo $student->class->name; ?></td>
                        <td><?php echo $student->session->name; ?></td>
                        <td>
                            <a href="<?php echo site_url(route_to('admin.students.print-letter',$student->id))?>" class="btn btn-sm btn-danger">Letter</a>
                            <a href="<?php echo site_url(route_to('admin.students.print-transcript',$student->id))?>" class="btn btn-warning btn-sm">Transcript</a>
                            <a class="btn btn-sm btn-info" href="#!" data-toggle="modal"
                               data-target=".edit_letter<?php echo $student->id; ?>">Edit</a>
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>">View Profile</a>
                                    <?php do_action('student_action_links'); ?>
                                </div>
                            </div>
                            <div class="modal fade edit_letter<?php echo $student->id; ?>" tabindex="-1"
                                 role="dialog" aria-labelledby="modal-default"
                                 style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                    <div class="modal-content">
                                        <form class="ajaxForm" loader="true" method="post"
                                              action="<?php echo site_url(route_to('admin.students.update-letter', $student->id)); ?>">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modal-title-default">Edit
                                                    Letter</h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sess">Passport</label>
                                                            <input type="file" class="form-control" name="letter_photo" accept="image/*"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="class_when_leaving">Student's Class When Leaving</label>
                                                            <input type="text"  class="form-control" name="class_when_leaving"  value="<?php echo get_option('class_when_leaving'.$student->id); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="remaining_payment">Remaining Payment</label>
                                                            <input type="number"  step="0.001" class="form-control" name="remaining_payment"  value="<?php echo get_option('remaining_payment'.$student->id); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="reason_for_leaving">Reason For Leaving</label>
                                                            <input type="text"  class="form-control" name="reason_for_leaving"  value="<?php echo get_option('reason_for_leaving'.$student->id); ?>"
                                                                   required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="learning_program">Learning Program</label><br/>
                                                            <input type="text" class="form-control" name="learning_program" value="<?php echo get_option('learning_program'.$student->id)?>" required>

                                                        </div>
                                                        <div class="form-group">
                                                            <label for="class_to_promote">Class to Promote Student to</label>
                                                            <input type="text"  class="form-control" name="class_to_promote"  value="<?php echo get_option('class_to_promote'.$student->id); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date_of_departure">Date Of Departure</label>
                                                            <input type="date"  class="form-control datepicker" name="date_of_departure"  value="<?php echo get_option('date_of_departure'.$student->id); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="student_conduct">Conduct</label>
                                                            <input type="text"  class="form-control" name="student_conduct"  value="<?php echo get_option('student_conduct'.$student->id); ?>"
                                                                   required/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="letter_no">Letter No. Initial</label>
                                                    <input type="text"  class="form-control" name="letter_no"  value="<?php echo get_option('letter_no'.$student->id); ?>"
                                                           required/>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <button type="button" class="btn btn-link  ml-auto"
                                                        data-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>


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
?>
        </div>
        </div>
<script>
    $(document).ready(function () {
        $('#students-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
            ],
        });
    })
</script>
