<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $role->name; ?></h6><br/>
                    <span class="text-light"><?php echo $role->description; ?></span>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.users.roles.index')); ?>"
                       class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>
                        Back</a>
                    <a class="btn btn-warning btn-sm"
                       href="<?php echo site_url(route_to('admin.users.roles.edit', $role->id)); ?>"><i
                                class="fa fa-edit"></i> Edit</a>
                    <?php
                    if ($role->id != 1) {
                        ?>
                        <?php if (isSuperAdmin()):?>
                        <a class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to completely remove this role?')"
                           href="<?php echo site_url(route_to('admin.users.roles.delete', $role->id)); ?>"><i
                                    class="fa fa-trash"></i> Delete</a>
                        <?php endif;
                    }
                    ?>
                    <?php do_action('user_roles_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            $capabilities = apply_filters('user_capabilities', []);
            $perms = apply_filters('user_permissions', []);
            $capabilities = array_merge($capabilities, $perms);
            if ($capabilities && is_array($capabilities) && count($capabilities) > 0) {
                ?>
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th style="width: 5%">Allowed</th>
                        <th>Capability/Permission</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($capabilities as $capability) {
                        if (isset($capability['name']) && is_array($capability['capabilities'])) {
                            ?>
                            <tr>
                                <th colspan="2"><b><?php echo $capability['name']; ?></b></th>
                            </tr>

                            <?php
                            foreach ($capability['capabilities'] as $key => $cap) {
                                ?>
                                <tr>
                                    <td>
                                        <label class="custom-toggle custom-toggle-success">
                                            <input id="<?php echo $key; ?>"
                                                   disabled
                                                   type="checkbox" <?php echo old('capabilities.' . $key, @$role->capabilities[$key]) == 1 ? 'checked' : ''; ?>
                                                   name="capabilities[<?php echo $key; ?>]"
                                                   value="1"/>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                  data-label-on="Yes"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <?php echo $cap; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    No capabilities/Permissions available
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>