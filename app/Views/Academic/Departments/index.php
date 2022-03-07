<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Departments</h6>
                    <a href="<?php echo site_url(route_to("admin.registration.departments.excel"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.registration.departments.pdf')); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo site_url(route_to("admin.registration.departments.print"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_class"><i
                            class="fa fa-plus"></i> New Department
                    </button>
                    <?php do_action('departments_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_class" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                                  action="<?php echo site_url(route_to('admin.registration.departments.create')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Department</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Department Name</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Department Head</label>
                                        <select class="form-control" name="head" required>
                                            <option value="">Please select</option>
                                            <?php
                                            $teachers = (new \App\Models\Teachers())->where('session',active_session())->findAll();
                                            if($teachers && count($teachers) > 0) {
                                                foreach ($teachers as $teacher) {
                                                    ?>
                                                    <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->profile->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
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
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <?php
                    $depts = (new \App\Models\Departments())->where("session",active_session())->findAll();
                    if($depts && count($depts) > 0) {
                        ?>
                        <div class="table-responsive">
                            <table class="table" id="departments-datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Head</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $n = 0;
                                foreach ($depts as $dept) {
                                    $n++;
                                    ?>
                                    <tr>
                                        <td><?php echo $n; ?></td>
                                        <td><?php echo $dept->name; ?></td>
                                        <td><?php echo $dept->head ? $dept->head->profile->name : '-'; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" onclick="getSubjects(<?php echo $dept->id; ?>)">View</button>
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target=".edit_dept<?php echo $dept->id; ?>">Edit</button>
                                    <?php if (isSuperAdmin()):?>
                                            <a href="<?php echo site_url(route_to('admin.registration.departments.delete', $dept->id)); ?>" class="btn btn-sm btn-danger send-to-server-click" url="<?php echo site_url(route_to('admin.registration.departments.delete', $dept->id)); ?>" data="action:delete|id:<?php echo $dept->id; ?>" loader="true" warning-title="Delete Department" warning-message="Are you sure you want to delete this department?">Delete</a>
                                          <?php endif;?>
                                            <div class="modal fade edit_dept<?php echo $dept->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                                 style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                                    <div class="modal-content">
                                                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                                                              action="<?php echo site_url(route_to('admin.registration.departments.create')); ?>">
                                                            <input type="hidden" name="id" value="<?php echo $dept->id; ?>" />
                                                            <div class="modal-header">
                                                                <h6 class="modal-title" id="modal-title-default">Edit Department</h6>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="sess">Department Name</label>
                                                                    <input type="text" class="form-control" name="name"
                                                                           value="<?php echo old('name', $dept->name) ?>" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="sess">Department Head</label>
                                                                    <select class="form-control" name="head" required>
                                                                        <option value="">Please select</option>
                                                                        <?php
                                                                        $teachers = (new \App\Models\Teachers())->where('session',active_session())->findAll();
                                                                        if($teachers && count($teachers) > 0) {
                                                                            foreach ($teachers as $teacher) {
                                                                                ?>
                                                                                <option <?php echo ($dept->head && $dept->head->id == $teacher->id) ? 'selected' : ''; ?> value="<?php echo $teacher->id; ?>"><?php echo $teacher->profile->name; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
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
                        <div class="alert alert-warning">
                            No departments found
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-5">
                    <div id="ajaxContent">
                        <div class="alert alert-info">
                            Please click 'View' on the department list to view and manage its subjects
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getSubjects(id) {
        var e = {
            url: "<?php echo site_url(route_to('admin.registration.department.ajax_get_subjects')); ?>",
            data: "department="+id,
            loader: true
        }

        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
        })
    }

    $(document).ready(function () {
        $('#departments-datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                // {
                //     extend: 'excel',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3 ]
                //     }
                // },
                // {
                //     extend: 'pdf',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3 ]
                //     }
                // },
                // {
                //     extend: 'print',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3 ]
                //     }
                // },
            ],
        });
    })
</script>