<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SMS Settings</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('sms_settings_quick_action_buttons'); ?>
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
            $active_gateway = get_option('active_sms_gateway', FALSE);
            $sms_gateways = apply_filters('sms_gateways', []);

            if ($sms_gateways && count($sms_gateways) > 0) {
                ?>
                <div class="accordion" id="accordionExample">
                    <?php
                    foreach ($sms_gateways as $key => $sms_gateway) {
                        ?>
                        <div class="card">
                            <div class="card-header" id="h<?php echo $key; ?>" data-toggle="collapse"
                                 data-target="#<?php echo $key; ?>" aria-expanded="false"
                                 aria-controls="<?php echo $key; ?>">
                                <h5 class="mb-0"><?php echo $sms_gateway['title']; ?>
                                    <div class="pull-right">
                                        <?php
                                        if ($active_gateway == $key) {
                                            ?>

                                            <span class="badge badge-success pull-right">
                                                Active
                                            </span>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </h5>
                            </div>
                            <div id="<?php echo $key; ?>" class="collapse" aria-labelledby="h<?php echo $key; ?>"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    <?php
                                    if($active_gateway != $key) {
                                        ?>
                                        <a href="<?php echo site_url(route_to('admin.settings.sms_activate_gateway', $key)) ?>" class="btn btn-sm btn-success send-to-server-click" url="<?php echo site_url(route_to('admin.settings.sms_activate_gateway', $key)) ?>" data="action:activate|gateway:<?php echo $key; ?>" warning-title="Activate Gateway" warning-message="Are you sure you want to activate <?php echo $sms_gateway['title']; ?>"  loader="true" >Activate Gateway</a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo site_url(route_to('admin.settings.sms_deactivate_gateway', $key)) ?>" class="btn btn-sm btn-danger send-to-server-click" url="<?php echo site_url(route_to('admin.settings.sms_deactivate_gateway', $key)) ?>" data="action:deactivate|gateway:<?php echo $key; ?>" warning-title="Deactivate Gateway" warning-message="Are you sure you want to deactivate <?php echo $sms_gateway['title']; ?>"  loader="true">Deactivate Gateway</a>
                                        <?php
                                    }
                                    ?>
                                    <br/><br/>
                                    <p><?php echo $sms_gateway['description']; ?></p>
                                    <form loader="true" class="ajaxForm" method="post" data-parsley-validate
                                          action="<?php echo site_url(route_to('admin.settings.sms_settings_save')); ?>">
                                        <?php
                                        do_action($key . '_settings');
                                        ?>
                                        <input type="hidden" name="key" value="<?php echo $key; ?>"/>
                                        <button type="submit" class="btn btn-success">Save Settings</button>
                                        <button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#modal<?php echo $key; ?>" >Test Gateway</button>
                                    </form>
                                    <div class="modal fade" id="modal<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="example<?php echo $key; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form method="post" class="ajaxForm" data-parsley-validate loader="true" action="<?php echo site_url(route_to('admin.settings.sms_test_gateway', $key)); ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Test <?php echo $sms_gateway['title']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Recipient Phone Number</label>
                                                            <input type="text" name="recipient" class="form-control" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Message</label>
                                                            <textarea class="form-control" name="message" required ></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Send SMS</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-warning">
                    No SMS gateways have been added
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>