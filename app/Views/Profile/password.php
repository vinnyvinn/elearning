<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Change Password</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('change_password_quick_action_buttons', $user); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <form method="post" class="ajaxForm" loader="true" data-parsley-validate action="<?php echo site_url(route_to('profile.change_password.post')); ?>">
                <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
                <div class="form-group">
                    <label for="pass">Old Password</label>
                    <input type="password" id="pass" name="password" class="form-control" value="<?php echo old('password'); ?>" placeholder="Old Password" required />
                </div>
                <div class="form-group">
                    <label for="pass1">New Password</label>
                    <input type="password" id="pass1" name="new_password" data-parsley-length="[8,32]" class="form-control" value="<?php echo old('new_password'); ?>" placeholder="New Password" required />
                </div>
                <div class="form-group">
                    <label for="pass2">Confirm New Password</label>
                    <input type="password" id="pass2" name="confirm_password" data-parsley-length="[8,32]" class="form-control" value="<?php echo old('confirm_password'); ?>" placeholder="Confirm New Password" required />
                </div>
                <div class="">
                    <button type="submit" class="btn btn-success btn-block">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>