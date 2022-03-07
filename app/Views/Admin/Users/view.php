<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($user->first_name . ' ' . $user->last_name); ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.users.edit', $user->id)); ?>"
                       class="btn btn-sm btn-warning"><i class="fa fa-user-edit"></i> Edit</a>
                    <?php if (isSuperAdmin()):?>
                    <a onclick="return confirm('Are you sure you want to completely remove this user?')" href="<?php echo site_url(route_to('admin.users.delete', $user->id)); ?>"
                       class="btn btn-sm btn-danger"><i class="fa fa-user-minus"></i> Delete</a>
                    <?php endif;?>
                    <?php do_action('user_profile_quick_action_buttons', $user); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <?php
    //dd($user);
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card card-profile">
                <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>" alt="Image placeholder"
                     class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <img src="<?php echo $user->avatar; ?>" class="rounded-circle" alt="<?php echo ucwords($user->first_name.' '.$user->last_name); ?>">
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                        <a href="#" class="btn btn-sm btn-default float-right">Message</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div>
                                    <span class="heading">22</span>
                                    <span class="description">Friends</span>
                                </div>
                                <div>
                                    <span class="heading">10</span>
                                    <span class="description">Photos</span>
                                </div>
                                <div>
                                    <span class="heading">89</span>
                                    <span class="description">Comments</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="h3">
                            <?php echo ucwords($user->first_name . ' ' . $user->last_name); ?>
                            <span class="font-weight-light">, 27</span>
                        </h5>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i>Bucharest, Romania
                        </div>
                        <div class="h5 mt-4">
                            <i class="ni business_briefcase-24 mr-2"></i>Software Developer
                        </div>
                        <div>
                            <i class="ni education_hat mr-2"></i>University of Computer Science
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8"></div>
    </div>
</div>