<?php




?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Application Details</h6><br/>
                    <small class="text-white"><?php echo $student->name; ?></small>
                </div>
                <div class="col-lg-6 col-5 text-right">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <?php
            //d($student);
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-profile">
                        <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>"
                             alt="<?php echo $student->name; ?>" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#!">
                                        <img src="<?php echo base_url('uploads/avatars/'.$student->info->profile_pic); ?>" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-6">
                            <div class="text-center">
                                <h5 class="h3"><?php echo $student->name; ?>
                                    <span class="font-weight-light">, <?php echo $student->gender ? $student->gender : '-'; ?></span>
                                </h5>
                                <div>
                                    <h3>Enrolling for</h3>
                                    <h1><b><?php echo (new \App\Models\Classes())->find($student->info->class)->name; ?></b></h1>
                                </div>
                                <div>
                                    <h3>Application Date</h3>
                                    <h5><?php echo $student->created_at->format('d/m/Y, h:i A'); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card-profile-image ">
                                                    <img src="<?php echo base_url('uploads/avatars/'.$student->parent->avatar); ?>" class="img">
                                                </div>
                                            </div>
                                            <div class="col-md-8">

                                                <h5 class="h3 d-inline">
                                                    <?php echo @$student->parent->surname.' '.$student->parent->first_name.' '.$student->parent->last_name; ?>
                                                </h5>
                                                <div class="h5 font-weight-300">
                                                    <i class="ni location_pin mr-2"></i><?php echo $student->parent->subcity; ?>
                                                    , <?php echo $student->parent->woreda; ?>
                                                    , <?php echo $student->parent->house_number; ?>
                                                </div>
                                                <h5 class="h4">Phone (Mobile)</h5>
                                                <?php echo $student->parent->mobile_number; ?>
                                                <h5 class="h4">Phone (Work)</h5>
                                                <?php echo $student->parent->work_phone; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Previous School</h4>
                                    <?php
                                    echo @$student->info->previous_school;
                                    ?>
                                    <h4>Deposit Slip</h4>
                                    <?php
                                    if(isset($student->info->deposit_slip) && $student->info->deposit_slip != '') {
                                        ?>
                                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.registration.online.students.download_slip', $student->id)); ?>"><i class="fa fa-download"></i> Download</a>
                                        <?php
                                    } else {
                                        echo "NONE UPLOADED";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
