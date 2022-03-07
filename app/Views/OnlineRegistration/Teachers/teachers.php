<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Online Registration</h6><br/>
                    <small class="text-white">Teachers Recruitment</small>
                    <a href="<?php echo site_url(route_to("admin.teachers-recruitment.export-excel"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.teachers-recruitment.pdf')); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo site_url(route_to("admin.teachers-recruitment.print"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
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
        </div>
        <div class="card-body">
            <?php
            //d($teachers);
            ?>
            <div class="table-responsive">
                <table class="table" id="teachers-table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>D.O.B</th>
                        <th>Contact</th>
                        <th>Application Date</th>
                        <th>Actions</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($teachers as $teacher) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $teacher->name; ?></td>
                            <td><?php echo $teacher->dob; ?></td>
                            <td><?php echo @$teacher->info->phone_number; ?></td>
                            <td><?php echo $teacher->created_at->format('d/m/Y h:i A'); ?></td>
                            <td>
                                <?php if ($teacher->status =='pending'):?>
                                <a class="btn btn-primary btn-sm" onclick="return confirm('Are you sure you want to register this entry?');" href="<?php echo site_url(route_to('admin.registration.online.teachers.new_teachers.register', $teacher->id)) ?>">Register</a>
                               <?php endif;?>
                                <a class="btn btn-info btn-sm" href="<?php echo site_url(route_to('admin.registration.online.teachers.new_teachers.view', $teacher->id)) ?>">View</a>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');" href="<?php echo site_url(route_to('admin.registration.online.teachers.new_teachers.delete', $teacher->id)) ?>">Delete</a>
                        <?php endif;?>
                            </td>
                            <td>
                                <?php if (isset($teacher->info->status)):?>
                                    <?php if ($teacher->info->status ==0):?>
                                    <span class="badge badge-danger">Pending</span>
                                  <?php else:?>
                                <span class="badge badge-primary">Registered</span>
                                <?php endif;?>
                                    <?php else:?>
                                    <span class="badge badge-danger">Pending</span>
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
        $('#teachers-table').dataTable({
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
