<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-6">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($student->profile->name); ?></h6>
                </div>
                <div class="col-lg-6 col-6 text-right">
                    <a href="<?php echo site_url(route_to('admin.students.form.download', $student->id)); ?>"
                       target="_blank"
                       class="btn btn-sm btn-secondary"><i class="fa fa-cloud-download-alt"></i> Form</a>

                    <a href="<?php echo site_url(route_to('admin.students.form.print_student_id', $student->id)); ?>"
                       target="_blank"
                       class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> ID</a>

                    <a href="<?php echo site_url(route_to('admin.students.form.download_id', $student->id)); ?>"
                       target="_blank"
                       class="btn btn-sm btn-secondary"><i class="fa fa-cloud-download-alt"></i> ID</a>

                    <a href="<?php echo site_url(route_to('admin.students.edit', $student->id)); ?>"
                       class="btn btn-sm btn-warning"><i class="fa fa-user-edit"></i> Edit</a>

                    <?php if (isSuperAdmin()):?>
                    <a href="<?php echo site_url(route_to('admin.students.delete', $student->id)); ?>"
                        data="action:delete|id:<?php echo $user->id ?>"
                        warning-title="Delete student" warning-message="Are you sure you want to completely remove this student"
                        url="<?php echo site_url(route_to('admin.students.delete', $student->id)); ?>"
                        warning-button="Yes, Delete!" loader="true"
                       class="btn btn-sm btn-danger send-to-server-click"><i class="fa fa-user-minus"></i> Delete</a>
                    <?php endif;?>
                    <?php do_action('student_profile_quick_action_buttons', $student); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                           <h5 class="card-title text-uppercase text-muted mb-0">Admission Number</h5>
                           <span class="h2 font-weight-bold mb-0"><?php echo $student->admission_number; ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="fa fa-hashtag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Class</h5>
                            <span class="h2 font-weight-bold mb-0"><?php echo $student->class->name; ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                <i class="fa fa-house-damage"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Section</h5>
                            <span class="h2 font-weight-bold mb-0"><?php echo $student->section->name; ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                <i class="fa fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3 mt--3 bg-white">
        <div class="ct-example card-header-pills" style="padding-bottom: 0; margin-bottom: 0">
            <ul class="nav nav-tabs-code nav-justified">
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'profile' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'exams' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.students.view.exams', $student->id)); ?>">Exams</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'assignments' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.students.view.assignments', $student->id)); ?>">Assignments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo @$page == 'fees' ? 'active' : ''; ?>" href="<?php echo site_url(route_to('admin.students.view.fees', $student->id)); ?>">Student Fees</a>
                </li>
            </ul>
        </div>
    </div>
    <?php
    echo @$html;
    ?>
</div>