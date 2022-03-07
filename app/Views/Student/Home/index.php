<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php use App\Models\Assignments;
                    do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>" alt="Image placeholder"
                     class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="<?php echo $user->avatar; ?>" style="max-height:150px; width:150px" class="rounded-circle">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-2 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">

                    </div>
                </div>
                <div class="card-body pt-md-5 pt-sm-3">
                    <div class="text-center">
                        <h5 class="h3">
                            <?php echo $user->name; ?><span class="font-weight-light"></span>
                        </h5>
                        <div class="h5 font-weight-300">
                            <?php echo $student->admission_number; ?>
                        </div>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i><?php echo $user->usermeta('subcity', '-'); ?>
                            , <?php echo $user->usermeta('woreda', '-'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Progress track -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0">Events</h5>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- List group -->
                    <?php
                    $events = (new \App\Models\Events())->calendarEventsCurrentMonth();;
                    if($events && count($events) > 0) {
                        ?>
                        <div class="table-responsive">
                            <table class="table datatable" id="datatable-basic">
                                <thead class="thead-light">
                                <tr>
                                    <th>Event</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <!--                                    <th>Class</th>-->
                                    <!--                                    <th>Section</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $n = 0;
                                foreach ($events as $event) {
                                    $n++;
                                    ?>
                                    <tr>
                                        <td><?php echo $event->name; ?></td>
                                        <td><?php echo $event->starting_date->format('d/m/Y'); ?></td>
                                        <td><?php echo $event->ending_date ? $event->ending_date->format('d/m/Y') : ''; ?></td>
                                        <!--                                        <td>-->
                                        <?php //echo $event->class ? $event->class->name : ''; ?><!--</td>-->
                                        <!--                                        <td>-->
                                        <?php //echo $event->section ? $event->section->name : ''; ?><!--</td>-->
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
                            No events have been posted
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card bg-gradient-info border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Class</h5>
                                    <span class="h2 font-weight-bold mb-0 text-white"><?php echo $student->class->name; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                        <i class="ni ni-shop"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card bg-gradient-danger border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Section</h5>
                                    <span class="h2 font-weight-bold mb-0 text-white"><?php echo $student->section->name; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                        <i class="ni ni-bullet-list-67"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            // Check assignments
            $assignments = $student->assignments;
            if ($assignments && count($assignments)) {
                ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Assignments </h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo site_url(route_to('student.assignments')); ?>"
                                   class="btn btn-sm btn-primary"> <i class="fa fa-external-link-alt"></i> View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Deadline</th>
                                <th>File</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($assignments as $assignment) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $assignment->subject->name; ?></td>
                                    <td><?php echo $assignment->deadline; ?></td>
                                    <td><a href="<?php echo $assignment->file; ?>">Download File</a></td>
                                    <td><?php echo $assignment->isSubmitted($student->id) ? '<span class="badge badge-success">Submitted</span>' : '<span class="badge badge-warning">Pending</span>'; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php do_action('student_homepage'); ?>
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">My Teachers </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-transparent">
                    <?php
                    $teachers = $student->teachers;
                    //d($teachers);
                    if ($teachers && count($teachers) > 0) {
                        ?>
                        <div class="row">
                            <?php
                            foreach ($teachers as $teacher) {
                                //var_dump($teacher->teacher);
//                                $xTeacher = (new \App\Models\Subjectteachers())->find($teacher->id);
//                                $xTeacher = $xTeacher->teacher;
                                ?>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <div class="align-items-center">
                                                <div class="align-content-center align-items-center">
                                                    <!-- Avatar -->
                                                    <a href="#"
                                                       class="avatar avatar-xl rounded-circle align-content-center">
                                                        <img alt="Image placeholder"
                                                             src="<?php echo $teacher->profile->avatar; ?>">
                                                    </a>
                                                </div>
                                                <div class="ml--2 align-items-center">
                                                    <h4 class="mb-0 text-center">
                                                        <a href="<?php echo site_url(route_to('student.messages.chat', $teacher->id)) ?>"><?php echo $teacher->profile->name .'- '.$teacher->teacher->teacher_number; ?></a>
                                                    </h4>
                                                    <p class="text-sm text-muted mb-0 text-center"><?php echo $teacher->subject->name; ?></p>
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
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>