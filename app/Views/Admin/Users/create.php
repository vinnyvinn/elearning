<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">New User</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('create_user_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="pb-3">
                            <img src="" alt="Image Preview" class="img-thumbnail"/>
                        </div>
                        <div class="form-group">
                            <label for="pic">Profile Image</label>
                            <input type="file" name="profile_pic" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sname">Surname</label>
                                    <input type="text" id="sname" name="surname" class="form-control" value="<?php echo old('surname'); ?>" placeholder="Surname" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <input type="text" id="fname" name="first_name" class="form-control" value="<?php echo old('first_name'); ?>" placeholder="First Name" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <input type="text" id="lname" name="last_name" value="<?php echo old('last_name'); ?>" class="form-control" placeholder="Last Name" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="uname">Username</label>
                                    <input type="text" id="uname" name="username" value="<?php echo old('username'); ?>" class="form-control" placeholder="Username" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">E-Mail</label>
                                    <input type="email" id="email" name="email" value="<?php echo old('email'); ?>" class="form-control" placeholder="Email" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" id="phone" name="phone" value="<?php echo old('phone'); ?>" class="form-control" placeholder="Phone Number" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company">Company</label>
                                    <input type="text" id="company" name="company" value="<?php echo old('company'); ?>" class="form-control" placeholder="Company" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pass">Password</label>
                                    <input type="password" id="pass" name="password" value="<?php echo old('password'); ?>" class="form-control" placeholder="Password" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pass1">Confirm Password</label>
                                    <input type="password" id="pass1" name="pass" value="<?php echo old('pass'); ?>" class="form-control" placeholder="Confirm Password" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php do_action('admin_create_user_form'); ?>
                <div class="">
                    <button type="submit" class="btn btn-success btn-block">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>