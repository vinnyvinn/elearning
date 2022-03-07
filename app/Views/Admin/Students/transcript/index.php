<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Transcript</h6>

                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_setting"><i
                                class="fa fa-plus"></i> Setting
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade new_setting" tabindex="-1" role="dialog" aria-labelledby="modal-default"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                  action="<?php echo site_url(route_to('admin.students.transcript-years')); ?>">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-default">Number of Years</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sess">Years</label>
                        <select name="no_of_years" class="form-control" required>
                            <option value="1" <?php if (get_option('no_of_years') ==1):?> selected <?php endif;?>>1</option>
                            <option value="2" <?php if (get_option('no_of_years') ==2):?> selected <?php endif;?>>2</option>
                            <option value="3" <?php if (get_option('no_of_years') ==3):?> selected <?php endif;?>>3</option>
                            <option value="4" <?php if (get_option('no_of_years') ==4):?> selected <?php endif;?>>4</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <form method="post" action="<?php echo site_url(route_to('admin.students.transcript'));?>">
                <div class="row">
                    <div class="col-md-4">

                        <select class="form-control form-control-sm select2" name="session" data-toggle="select2" id="class">
                            <option value="all">All Sessions</option>
                            <?php

                            $sessions = (new \App\Models\Sessions())->findAll();
                                foreach ($sessions as $session) {
                                    ?>
                                    <option value="<?php echo $session->id; ?>"><?php echo $session->name; ?></option>
                                    <?php
                                }

                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success btn-sm btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
        if ($students && count($students) > 0) {
            ?>
            <div class="table-responsive pt-2">
                <table class="table" id="students-table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Adm #</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Admission Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($students as $student) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td class="table-user">
                                <img src="<?php echo $student->profile->avatar ? $student->profile->avatar : ''; ?>" class="avatar rounded-circle mr-3">
                                <a href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>">
                                    <?php echo $student->profile->name; ?>
                                </a>
                            </td>
                            <td><?php echo $student->admission_number; ?></td>
                            <td><?php echo isset($student->class->name) ? $student->class->name : ''; ?></td>
                            <td><?php echo isset($student->section->name)? $student->section->name:''; ?></td>
                            <td><?php echo $student->created_at->format('d/m/Y'); ?></td>
                            <td>
                                <a href="<?php echo site_url(route_to('admin.students.print-transcript',$student->id))?>" class="btn btn-danger btn-sm">Transcript</a>
                                <a class="btn btn-sm btn-info" href="#!" data-toggle="modal"
                                   data-target=".edit<?php echo $student->id; ?>">Edit</a>
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>">View Profile</a>
                                        <?php do_action('student_action_links'); ?>
                                    </div>
                                    <div class="modal fade edit<?php echo $student->id; ?>" tabindex="-1"
                                         role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('admin.students.update-transcript', $student->id)); ?>">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Edit
                                                            Transcript</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Photo</label>
                                                            <input type="file" class="form-control" name="transcript_photo" accept="image/*"/>
                                                        </div>
                                                    <div class="form-group">
                                                        <label for="class_when_leaving">Date of Leaving</label>
                                                        <input type="text"  class="form-control datepicker" name="transcript_date_of_leaving"  value="<?php echo get_option('transcript_date_of_leaving'.$student->id); ?>"
                                                               required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="remaining_payment">Remarks</label>
                                                        <textarea name="transcript_remarks" rows="3" class="form-control" required><?php echo get_option('transcript_remarks'.$student->id); ?></textarea>
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
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            ?>
            <div class="card-body">
                <div class="alert alert-danger">
                    No students found
                </div>
            </div>
            <?php
        }
        ?>
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