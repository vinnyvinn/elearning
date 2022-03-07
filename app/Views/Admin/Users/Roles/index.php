<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">User Roles</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.users.roles.create')); ?>" class="btn btn-sm btn-success"><i class="fa fa-user-plus"></i> New Role</a>
                    <?php do_action('user_roles_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($roles && count($roles) > 0) {
                    $n = 0;
                    foreach ($roles as $role) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $role->name; ?></td>
                            <td><?php echo $role->description; ?></td>
                            <td>

                                        <a class="btn btn-primary btn-sm" href="<?php echo site_url(route_to('admin.users.roles.capabilities', $role->id)); ?>"><i class="fa fa-eye"></i> View</a>
                                        <?php do_action('users_roles_table_actions', $role->id); ?>
                                        <a class="btn btn-warning btn-sm" href="<?php echo site_url(route_to('admin.users.roles.edit', $role->id)); ?>"><i class="fa fa-edit"></i> Edit</a>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to completely remove this role?')"
                                           href="<?php echo site_url(route_to('admin.users.roles.delete', $role->id)); ?>"><i
                                                class="fa fa-trash"></i> Delete</a>
                        <?php endif;?>
                                        <?php
                                        do_action('users_roles_list_action_links', $role->id);
                                        ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">No users</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>