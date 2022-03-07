<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Users</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.users.create')); ?>" class="btn btn-sm btn-success"><i class="fa fa-user-plus"></i> New User</a>
                    <?php do_action('users_quick_action_buttons'); ?>
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
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" /></th>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $ionAuth = new \App\Libraries\IonAuth();
                $users = (new App\Models\User())->findAll();
                if ($users && count($users) > 0) {
                    foreach ($users as $user) {
                        ?>
                        <tr>
                            <td><input type="checkbox" /> </td>
                            <td>
                                <div class="media align-items-center">
                                    <span class="avatar rounded-circle mr-3">
                                        <img alt="Avatar" src="<?php echo $user->avatar; ?>">
                                    </span>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm"><a href="<?php echo site_url(route_to('admin.users.profile', $user->id)); ?>"><?php echo $user->first_name . ' ' . $user->last_name; ?></a></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $user->email; ?></td>
                            <td><?php echo $user->phone; ?></td>
                            <td><?php echo ucwords($ionAuth->getUsersGroups($user->id)->getRow()->name); ?></td>
                            <td>
                                <label class="custom-toggle custom-toggle-success disabled">
                                    <input type="checkbox" disabled <?php echo $user->active == 1 ? 'checked' : ''; ?>>
                                    <span class="custom-toggle-slider rounded-circle disabled" data-label-off="No" data-label-on="Yes"></span>
                                </label>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item text-blue" href="<?php echo site_url(route_to('admin.users.profile', $user->id)); ?>">View Profile</a>
                                        <?php do_action('users_table_actions', $user->id); ?>
                                        <a class="dropdown-item text-warning" href="<?php echo site_url(route_to('admin.users.edit', $user->id)); ?>">Edit</a>
                                      <?php if (isSuperAdmin()):?>
                                        <a class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to completely remove this user?')" href="<?php echo site_url(route_to('admin.users.delete', $user->id)); ?>">Delete</a>
                                        <?php endif;
                                        do_action('admin_users_list_action_links', $user->id);
                                        ?>
                                    </div>
                                </div>
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