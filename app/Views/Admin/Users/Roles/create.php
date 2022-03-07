<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">New User Role</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.users.roles.index')); ?>"
                       class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>
                        Back</a>
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
            <form method="post" action="<?php echo current_url(); ?>">
                <h2 class="h4">Information</h2>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" value="<?php echo old('name'); ?>" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label for="desc">Description</label>
                    <textarea rows="5" class="form-control" name="description"
                              required><?php echo old('description'); ?></textarea>
                </div>
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
                                                       type="checkbox" <?php echo old('capabilities.' . $key) == 1 ? 'checked' : ''; ?>
                                                       name="capabilities[<?php echo $key; ?>]"
                                                       value="1"/>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                      data-label-on="Yes"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label for="<?php echo $key; ?>"> <?php echo $cap; ?></label>
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
                <hr/>
                <div class="pull-right">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>