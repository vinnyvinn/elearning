<?php




?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Online Registration</h6><br/>
                    <small class="text-white">Existing Students</small>
                    <a href="<?php echo base_url("admin/registration/online/existing-students-export-excel?class=$class");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo base_url("admin/registration/online/existing-students-pdf?class=$class"); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo base_url("admin/registration/online/existing-students-print?class=$class");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
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
            <form method="post" action="<?php echo site_url(route_to('admin.registration.online.students.existing'));?>">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control form-control-sm select2" name="class" data-toggle="select2" id="class">
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
                <table class="table" id="std-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Admission Number</th>
                            <th>D.O.B</th>
                            <th>Parent's Name</th>
                            <th>Parent's Contact</th>
                            <th>Application Date</th>
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
                            <td><?php echo $student->name; ?></td>
                            <td><?php echo $student->info->admission_number; ?></td>
                            <td><?php echo $student->dob; ?></td>
                            <td><?php echo @$student->parent->surname.' '.$student->parent->first_name.' '.$student->parent->last_name; ?></td>
                            <td><?php echo @$student->parent->mobile_number; ?></td>
                            <td><?php echo $student->created_at->format('d/m/Y h:i A'); ?></td>
                            <td>
                                <a class="btn btn-info btn-sm" href="<?php echo site_url(route_to('admin.registration.online.students.existing_students.view', $student->id)) ?>">View</a>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');" href="<?php echo site_url(route_to('admin.registration.online.students.existing_students.delete', $student->id)) ?>">Delete</a>
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

    $(document).ready(function () {
        $('#std-table').dataTable({
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