<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Settings</h6>
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
            <div class="">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#site-settings" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-settings mr-2"></i>Site Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#email-settings" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-email-83 mr-2"></i>E-Mail Settings</a>
                    </li>
                    <?php
                    $settings = apply_filters('admin_settings_page', []);
                    $pages = [];
                    if($settings && is_array($settings) && count($settings)) {
                        foreach($settings as $key=>$name) {
                            if(isset($key) && isset($name)) {
                                array_push($pages, $key);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#<?php echo $key ?>" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><?php echo $name ?></a>
                                </li>
                                <?php
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="site-settings" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                    <div>
                        <form class="form ajaxForm" loader="true" method="post" data-parsley-validate="" action="<?php echo site_url(route_to('admin.settings.site')); ?>">
                            <div class="form-group">
                                <label for="title">Website Title</label>
                                <input type="text" id="title" name="site_title" value="<?php echo get_parent_option('system', 'site_title', ''); ?>" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="logo">Site Logo</label>
                                <input type="file" id="logo" name="logo" accept="image/*" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <input type="text" class="form-control" name="currency" value="<?php echo get_option('currency', 'Kshs'); ?>" />
                            </div>
                            <?php
                            do_action('admin_settings_site');
                            ?>
                            <div>
                                <button type="submit" class="btn btn-primary"> Save Settings </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="email-settings" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                    <div>
                        <form class="form ajaxForm" loader="true" method="post" data-parsley-validate="" action="<?php echo site_url(route_to('admin.settings.email')); ?>">
                            <div class="form-group">
                                <label for="title">E-Mail Name</label>
                                <input type="text" id="title" name="email_name" value="<?php echo get_parent_option('system', 'email_name', ''); ?>" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="title">E-Mail</label>
                                <input type="email" id="title" name="email_email" value="<?php echo get_parent_option('system', 'email_email', ''); ?>" class="form-control" required />
                            </div>
                            <?php
                            do_action('admin_settings_email');
                            ?>
                            <div>
                                <button type="submit" class="btn btn-primary"> Save Settings </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                $pages = array_unique($pages);
                if(is_array($pages) && count($pages) > 0) {
                    foreach($pages as $page) {
                        ?>
                        <div class="tab-pane fade" id="<?php echo $page; ?>" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                            <?php do_action($page); ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>