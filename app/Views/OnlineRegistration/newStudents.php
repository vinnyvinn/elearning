<?php ?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Online Registration</h6><br/>
                    <small class="text-white">New Students</small>
                    <a href="<?php echo base_url("admin/registration/online/new-students-export-excel?class=$class");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo base_url("admin/registration/online/new-students-pdf?class=$class"); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo base_url("admin/registration/online/new-students-print?class=$class");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <form method="post" action="<?php echo site_url(route_to('admin.registration.online.students.new_students'));?>">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control form-control-sm select2" name="class" data-toggle="select2" id="class">
                            <option value="all">All</option>
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
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success btn-sm btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php
            //d($students);
            ?>
            <div class="table-responsive">
                <table class="table" id="students-table">
                     <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>D.O.B</th>
                            <th>Class</th>
                            <th>Parent's Name</th>
                            <th>Parent's Contact</th>
                            <th>Application Date</th>
                            <th>Actions</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($students as $student) {
                        $n++;
                        $class = (new \App\Models\Classes())->find((int)$student->info->class);
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $student->name; ?></td>
                            <td><?php echo $student->dob; ?></td>
                            <td><?php echo isset($class->name) ? $class->name : ''; ?></td>
                            <td><?php echo @$student->parent->surname.' '.$student->parent->first_name.' '.$student->parent->last_name; ?></td>
                            <td><?php echo @$student->parent->mobile_number; ?></td>
                            <td><?php echo $student->created_at->format('d/m/Y h:i A'); ?></td>
                            <td>
                                <?php if ($student->status =='pending'):?>
<!--                                    <a class="btn btn-primary btn-sm" onclick="return confirm('Are you sure you want to register this entry?');" href="--><?php //echo site_url(route_to('admin.registration.online.students.new_students.register', $student->id)) ?><!--">Register</a>-->
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=".new_student_<?php echo $student->id?>">Register</button>

                                    <div class="modal fade new_student_<?php echo $student->id?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form method="post"
                                                      action="<?php echo site_url(route_to('admin.registration.online.students.new_students.register', $student->id)); ?>">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Register Student</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group col-md-11">
                                                            <label for="sess">Class</label>
                                                            <select class="form-control form-control-sm select2 class" name="class" data-toggle="select2" id="class">
                                                                <option>--Select Class --</option>
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
                                                        <div class="form-group col-md-11">
                                                            <label for="sess">Section</label>
                                                            <select class="form-control select2 form-control-sm section" data-toggle="select2" id="section" name="section" required>
                                                                <option value=""> -- Section --</option>
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
                                <?php endif;?>
                                <a class="btn btn-info btn-sm" href="<?php echo site_url(route_to('admin.registration.online.students.new_students.view', $student->id)) ?>">View</a>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');" href="<?php echo site_url(route_to('admin.registration.online.students.new_students.delete', $student->id)) ?>">Delete</a>
                        <?php endif;?>
                            </td>
                            <td>

                                    <?php if ($student->status =='pending'):?>
                                        <span class="badge badge-danger">Pending</span>
                                    <?php else:?>
                                        <span class="badge badge-primary">Registered</span>
                                    <?php endif;?>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('.class').on('change',function (){
        var classId = $(this).val();

        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var url = '<?php echo site_url('ajax/class/') ?>' + classId + "/sections";
            console.log(url)
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('.section').html(data);
            });
        }
    })
    var getSections = function () {

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
                // {
                //     extend: 'excel',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3, 4, 5 ]
                //     }
                // },
                // {
                //     extend: 'pdf',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3, 4, 5 ]
                //     }
                // },
                // {
                //     extend: 'print',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3, 4, 5 ]
                //     }
                // },
            ],
        });
    })
</script>