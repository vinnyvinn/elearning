<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Modules</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="#!" onclick="$('#newModule').toggle(500); return false; " class="btn btn-sm btn-neutral"><i class="fa fa-plug"></i> New Module</a>
                </div>
            </div>
            <div id="newModule" style="display: none">
                <div class="alert alert-dark">
                    <form method="post" action="<?php echo site_url(route_to('admin.modules.upload')); ?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Module File (.zip)</label>
                            <input type="file" name="module" accept=".zip" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload Module</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        $installed_plugins = getInstalledPlugins();
        if ($installed_plugins && is_array($installed_plugins) && count($installed_plugins) > 0) {
            ?>
            <table class="card-body table table-hover">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox"/>
                    </th>
                    <th>Module</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($installed_plugins as $installed_plugin) {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox"/>
                        </td>
                        <td>
                            <?php echo $installed_plugin['title']; ?><br/><br/>
                            <?php
                            if (activeModules($installed_plugin['id'])) {
                                ?>
                                <a class="text-warning"
                                   href="<?php echo site_url(route_to('admin.modules.deactivate', $installed_plugin['id'])); ?>">Deactivate</a>
                                <?php
                            } else {
                                ?>
                                <a href="<?php echo site_url(route_to('admin.modules.activate', $installed_plugin['id'])); ?>">Activate</a>
                                |
                                <?php
                                if (is_writable(dirname(MODULES_PATH . $installed_plugin['fn']))) {
                                    ?>
                                    <?php if (isSuperAdmin()):?>
                                    <a class="text-danger"
                                       href="<?php echo site_url(route_to('admin.modules.delete', $installed_plugin['id'])); ?>">Delete</a>
                                    <?php endif;
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $installed_plugin['description']; ?><br/><br/>
                            <?php echo 'Version ' . $installed_plugin['version']; ?> |
                            <?php echo 'Author ' . $installed_plugin['author']; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <div class="card-body">
                <div class="alert alert-warning">
                    No Modules have been added yet
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>