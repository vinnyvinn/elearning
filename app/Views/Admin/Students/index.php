<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Students</h6>
                    <a href="<?php echo base_url("admin/users/students/export?class=$class&section=$section");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.students.list.pdf',$class,$section?:0)); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo base_url("admin/users/students/print-list?class=$class&section=$section");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>

                <div class="col-lg-6 col-5 text-right">
                     <?php if ($section !==0):?>
                    <a href="<?php echo site_url(route_to('admin.students.form.print_bulk_id',$class,$section?:0)); ?>" target="_blank" class="btn btn-sm btn-success"><i
                                class="fa fa-print"></i> Student IDS</a>
                    <a href="<?php echo site_url(route_to('admin.students.form.pdf_bulk_id',$class,$section?:0)); ?>" target="_blank" class="btn btn-sm btn-success"><i
                                class="fa fa-cloud-download-alt"></i> Student IDS</a>
                    <a href="<?php echo site_url(route_to('admin.students.form.bulk_pdf',$class,$section?:0)); ?>" target="_blank" class="btn btn-sm btn-success"><i
                                class="fa fa-cloud-download-alt"></i> Student Forms</a>
                         <a href="<?php echo site_url(route_to('admin.students.form.compact',$class,$section?:0)); ?>" target="_blank" class="btn btn-sm btn-success"><i
                                     class="fa fa-print"></i> Compact Forms</a>
                    <?php else:?>
                         <a>
                             <p style="color: sandybrown !important;">Filter first to download Students IDS and Forms</p>
                         </a>
                    <?php endif;?>
                    <a href="<?php echo site_url(route_to('admin.students.create')); ?>" class="btn btn-sm btn-success"><i
                                class="fa fa-user-plus"></i> New Student</a>
                    <?php do_action('students_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <form method="post" action="<?php echo site_url(route_to('admin.students.index'));?>">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control form-control-sm select2" name="class" data-toggle="select2" id="class" onchange="getSections()">
                            <option value="all">All Students</option>
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
                    <div class="col-md-4">
                        <select class="form-control select2 form-control-sm" data-toggle="select2" id="section" name="section" required>
                            <option value=""> -- Section --</option>
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
                        <th>Gender</th>
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
                                    <?php echo $student->profile->name;?>
                                </a>
                            </td>
                            <td><?php echo $student->profile->gender; ?></td>
                            <td><?php echo $student->admission_number; ?></td>
                            <td><?php echo isset($student->class->name) ? $student->class->name : ''; ?></td>
                            <td><?php echo isset($student->section->name)? $student->section->name:''; ?></td>
                            <td><?php echo $student->admission_date ? date('d/m/Y',strtotime($student->admission_date)) : ''; ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>">View Profile</a>
                                        <?php do_action('student_action_links'); ?>
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