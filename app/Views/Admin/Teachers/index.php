<?php
$teachers = (new \App\Models\Teachers())->orderBy('id', 'DESC')->where('session',active_session())->findAll();
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Teachers</h6>
                    <a href="<?php echo site_url(route_to("admin.teachers.export-excel"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.teachers.pdf')); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo site_url(route_to("admin.teachers.print-list"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.teachers.create')); ?>" class="btn btn-sm btn-success"><i
                            class="fa fa-user-plus"></i> New Teacher</a>
                    <?php do_action('teachers_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header"></div>
        <?php
        if($teachers && count($teachers) > 0) {
            ?>
            <div class="table-responsive pt-2">
                <table class="table" id="teachers-datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Teacher ID</th>
                            <th>Phone</th>
                            <th>Actions</th>
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
                            <td class="table-user">
                                <img src="<?php echo $teacher->profile->avatar; ?>" class="avatar rounded-circle mr-3">
                                <a href="<?php echo site_url(route_to('admin.teachers.view', $teacher->id)); ?>">
                                    <?php echo $teacher->profile->name; ?>
                                </a>
                            </td>
                            <td><?php echo $teacher->teacher_number; ?></td>
                            <td><?php echo $teacher->profile->phone; ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="<?php echo site_url(route_to('admin.teachers.view', $teacher->id)); ?>">View Profile</a>
                                        <?php do_action('teacher_action_links', $teacher); ?>
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
                    No teachers were found in the system
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#teachers-datatable').dataTable({
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