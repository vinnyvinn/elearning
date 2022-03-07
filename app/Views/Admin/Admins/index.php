<?php
$admins = (new \App\Models\Admins())->findAll();
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Admins</h6>
                    <a href="<?php echo site_url(route_to("admin.admins.export-excel"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.admins.pdf')); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo site_url(route_to("admin.admins.print-list"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.admins.create')); ?>" class="btn btn-sm btn-success"><i class="fa fa-user-plus"></i> New Admin</a>
                    <?php do_action('admins_quick_action_buttons'); ?>
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
        <?php
        if($admins && count($admins) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table" id="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Username</th>
                            <th>E-Mail</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($admins as $admin) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td>
                                <div class="media align-items-center">
                                    <span class="avatar rounded-circle mr-3">
                                        <img alt="Avatar" src="<?php echo $admin->avatar; ?>">
                                    </span>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm"><a href="#!"><?php echo $admin->name; ?></a></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $admin->phone; ?></td>
                            <td><?php echo $admin->username; ?></td>
                            <td><?php echo $admin->email; ?></td>
                            <td><?php echo $admin->active == 1 ? '<span class="badge badge-success">ACTIVE</span>' : '<span class="badge badge-danger">INACTIVE</span>'; ?></td>
                            <td>
                                <?php if (!checkSuperadmin($admin->id) ||  isSuperAdmin()):?>
                                <a class="btn btn-sm btn-warning" href="<?php echo site_url(route_to('admin.admins.edit', $admin->id)); ?>"><i class="fa fa-user-edit"></i> Edit</a>
                                 <?php endif;?>
                                <?php if (!checkSuperadmin($admin->id)):?>
                                <a class="btn btn-sm btn-danger" href="<?php echo site_url(route_to('admin.admins.delete', $admin->id)); ?>"><i class="fa fa-info-circle"></i> Change Status</a>
                                <?php endif;?>
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
                Umm, you should never see this message, but just in case you do, it means you have broken your system
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#admin-table').dataTable({
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