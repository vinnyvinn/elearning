<?php
$parent = (new \App\Models\Parents())->find($parent->id);
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($parent->name); ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php if (isSuperAdmin()):?>
                        <a href="<?php echo site_url(route_to('admin.parents.delete', $parent->id)); ?>"
                           data="action:delete|id:<?php echo $parent->id ?>"
                           warning-title="Delete Parent" warning-message="Are you sure you want to completely remove this parent"
                           url="<?php echo site_url(route_to('admin.parents.delete', $parent->id)); ?>"
                           warning-button="Yes, Delete!" loader="true"
                           class="btn btn-sm btn-danger send-to-server-click"><i class="fa fa-user-minus"></i> Delete</a>
                    <?php endif;?>
                    <?php do_action('parent_profile_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-xl-4 order-xl-2">
                    <div class="card card-profile">
                        <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>" alt="Image placeholder" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="<?php echo $parent->avatar; ?>" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <a href="#!" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                                <a href="#!" class="btn btn-sm btn-default float-right">Message</a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="text-center">
                                <h5 class="h3">
                                    <?php echo $parent->name; ?>
                                </h5>
                                <div class="h5 font-weight-300">
                                    <i class="ni location_pin mr-2"></i><?php echo $parent->profile->usermeta('woreda') ?>, <?php echo $parent->profile->usermeta('subcity') ?>, <?php echo $parent->profile->usermeta('house_number') ?>
                                </div>
                                <div>
                                    <i class="fa fa-phone-alt"></i>  <?php echo $parent->phone; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 order-xl-1">
                    <div class="card">
                        <div class="card-header ">
                            <h3>Parents Student(s)</h3>
                        </div>
                        <div class="card-body">
                            <?php
                            $students = $parent->students;
                            if($students && count($students) > 0) {
                                ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student</th>
                                                <th>Admission #</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $n = 0;
                                        foreach ($students as $student) {
                                            $n++;
                                            ?>
                                            <tr>
                                                <td><?php echo $n; ?></td>
                                                <td><a href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>"><?php echo $student->profile->name; ?></a></td>
                                                <td><?php echo $student->admission_number; ?></td>
                                                <td><a href="<?php echo site_url(route_to('admin.classes.view', $student->class->id)); ?>"><?php echo $student->class->name; ?></a></td>
                                                <td><a href="<?php echo site_url(route_to('admin.class.sections.view', $student->section->id)); ?>"><?php echo $student->section->name; ?></a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-warning">
                                    This parent has no students. Please contact the developer because you should not see this message
                                </div>
                                <?php
                            }
                            ?>
                            <?php
                            //d($parent);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>