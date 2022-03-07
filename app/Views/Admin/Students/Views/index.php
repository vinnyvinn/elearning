<div class="row">
    <div class="col-md-4">
        <div class="card card-profile">
            <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>"
                 alt="<?php echo $student->profile->name; ?>" class="card-img-top">
            <div class="row justify-content-center">
                <div class="col-lg-3 order-lg-2">
                    <div class="card-profile-image">
                        <a href="#!">
                            <img src="<?php echo $student->profile->avatar; ?>" class="rounded-circle">
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-6">
                <div class="text-center">
                    <h5 class="h3"><?php echo $student->profile->name; ?>
                        <span class="font-weight-light">, <?php echo $student->profile->gender ? $student->profile->gender : '-'; ?></span>
                    </h5>
                    <div class="h5 font-weight-300">
                        <i class="ni location_pin mr-2"></i><?php echo $student->admission_number; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                       <div class="text-center">
                       <h2 class="mt-2">Parent</h2>
                       </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body pt-0">
                            <div class="text-center">
                                <hr/>
                                <div class="card-profile-image" style="margin-bottom: 7rem">
                                    <?php
                                    if ($student->parent->usermeta('parent_avatar')):
                                        $avatar = base_url("uploads/avatars/".$student->parent->usermeta('parent_avatar')) ;
                                    else:
                                        $avatar = base_url('assets/img/default.jpg');
                                    endif;
                                    ?>
                                    ?>
                                    <img src="<?php echo $avatar; ?>" class="rounded-circle">
                                </div>
                                <h5 class="h3">
                                    <?php echo $student->parent->name; ?><span
                                            class="font-weight-light">, <?php echo $student->parent->usermeta('gender'); ?></span>
                                </h5>
                                <div class="h5 font-weight-300">
                                    <i class="ni location_pin mr-2"></i><?php echo $student->parent->usermeta('subcity', 'Subcity'); ?>
                                    , <?php echo $student->parent->usermeta('woreda', 'Woreda'); ?>
                                    , <?php echo $student->parent->usermeta('house_number', 'House Number'); ?>
                                </div>
                                <h5 class="h4">Phone (Mobile)</h5>
                                <?php echo $student->parent->usermeta('mobile_phone_number'); ?>
                                <h5 class="h4">Phone (Work)</h5>
                                <?php echo $student->parent->usermeta('mobile_phone_work'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if (isset($student->contact)) {

                            ?>
                            <div class="card-body pt-0">
                                <div class="text-center">
                                    <h2 class="mt-2">Emergency Contact</h2>
                                    <hr/>
                                    <h5 class="h3">
                                        <?php echo isset($student->contact->name) ? $student->contact->name :''; ?>
                                    </h5>
                                    <div class="h5 font-weight-300">
                                        <i class="ni location_pin mr-2"></i><?php echo isset($student->contact->subcity) ? $student->contact->subcity :''; ?>
                                        , <?php echo isset($student->contact->woreda) ? $student->contact->woreda :''; ?>
                                        , <?php echo isset($student->contact->house_number) ? $student->contact->house_number :''; ?>
                                    </div>
                                    <h5 class="h4">
                                        Phone (Mobile)
                                    </h5>
                                    <?php echo isset($student->contact->phone_mobile) ? $student->contact->phone_mobile :''; ?>
                                    <h5 class="h4">
                                        Phone (Work)
                                    </h5>
                                    <?php echo isset($student->contact->phone_work) ? $student->contact->phone_work :''; ?>
                                </div>
                            </div>
                            <?php
                        } else {

                            //No Emergency Contact information

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>