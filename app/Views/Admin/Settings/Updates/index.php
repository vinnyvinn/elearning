<?php


$updater = new \App\Libraries\Updater(WRITEPATH.'cache', FCPATH, 120);
$current_version = get_option('_application_version', '0.0.1');
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">System Updates</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('settings_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <h5>Current Version: <?php echo $current_version; ?></h5>
        </div>
        <div class="card-body">
            <?php
            $cache = new Desarrolla2\Cache\File(WRITEPATH . 'cache');
            $updater->setCache($cache, 3600);

            $current_version = get_option('_application_version', '0.0.1');

            $updater->setCurrentVersion($current_version);
            //d($updater->checkUpdate(10));
            //d($updater->updates);

            try {
                if ($updater->checkUpdate(10) && is_array($updater->updates) && count($updater->updates) > 0) {
                    ?>
                    <div class="">
                        <p>New Updates Available</p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Version</th>
                                    <th>Release Date</th>
                                    <th>Update Notice</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $n = 0;
                                foreach ($updater->updates as $update) {
                                    $n++;
                                    ?>
                                    <tr>
                                        <th><?php echo $update['version']; ?></th>
                                        <td><?php echo $update['info']['date']; ?></td>
                                        <td><?php echo $update['info']['description']; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <br/>
                        <a class="btn btn-success"
                           href="<?php echo site_url(route_to('admin.settings.updates.run')); ?>">RUN UPDATE NOW</a>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-success">
                        [<?php echo $current_version; ?>] You have the latest version
                    </div>
                    <?php
                }
            } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
                ?>
                <div class="alert alert-danger">
                    <?php echo $e->getMessage(); ?>
                </div>
                <?php
            } catch (Exception $e) {
                ?>
                <div class="alert alert-danger">
                    <?php echo $e->getMessage(); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
