<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php use App\Models\Events;

                    do_action('admin_dashboard_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Students</h5>
                            <span class="h2 font-weight-bold mb-0"><?php
                                  echo count(getSession()->students_arr);
                                ?></span>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo site_url(route_to('admin.students.index')); ?>">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="fa fa-users"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Teachers</h5>
                            <span class="h2 font-weight-bold mb-0"><?php
                           echo number_format(count((new \App\Models\Teachers())->where('session',active_session())->findAll())); ?></span>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo site_url(route_to('admin.teachers.index')); ?>">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="fa fa-chalkboard-teacher"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Parents</h5>
                            <span class="h2 font-weight-bold mb-0"><?php echo count((new \App\Models\Parents())->getParents_()); ?></span>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo site_url(route_to('admin.parents.index')); ?>">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="fa fa-user-friends"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-gradient-success">
                <div class="card-body justify-content-center align-content-center">
                    <h3 class="card-title text-white text-center">Today's Student Attendance</h3>
                    <h1 class="card-title text-white text-center"><?php echo number_format((new \App\Models\Attendance())->daysStudentAttendance()); ?></h1>
                    <a class="btn btn-sm btn-block" href="<?php echo site_url(route_to('admin.attendance.students_monthly')); ?>">GO TO MONTHLY ATTENDANCE COUNTER</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-orange">
                <div class="card-body justify-content-center align-content-center">
                    <h3 class="card-title text-white text-center">Today's Teachers Attendance</h3>
                    <h1 class="card-title text-white text-center"><?php echo number_format((new \App\Models\Attendance())->daysTeachersAttendance()); ?></h1>
                    <a class="btn btn-sm btn-block" href="<?php echo site_url(route_to('admin.attendance.teachers_monthly')); ?>">GO TO MONTHLY ATTENDANCE COUNTER</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-success">
                <div class="card-body justify-content-center align-content-center">
                    <h3 class="card-title text-white text-center">Active Session</h3>
                    <?php
                    if($sess = getSession()) {
                        ?>
                        <h1 class="card-title text-white text-center"><?php echo $sess->name; ?></h1>
                        <?php
                    } else {
                        ?>
                        Please set up school session <a href="<?php echo site_url(route_to('admin.sessions.index')); ?>">HERE</a>
                        <?php
                    }
                    ?>
                    <a class="btn btn-sm btn-block" href="<?php echo site_url(route_to('admin.sessions.index')); ?>">MANAGE SESSIONS</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?php
            $events = (new \App\Models\Events())->calendarEvents();
            ?>
            <script>
                var schoolEvents = <?php echo json_encode($events); ?>
            </script>
            <div class="card card-calendar">
                <div class="card-header mb-0 pb-0">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6 col-7">
                            <h6 class="fullcalendar-title h2 d-inline-block mb-0">Events Calendar</h6>
                        </div>
                        <div class="col-lg-6 col-5 text-right">
                            <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                                <i class="fas fa-angle-left"></i>
                            </a>
                            <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">Month</a>
                            <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">Week</a>
                            <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">Day</a>
                            <?php do_action('admin_events_quick_action_buttons'); ?>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="calendar" data-toggle="calendar" id="calendar"></div>
                </div>
                <div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-secondary" role="document">
                        <div class="modal-content">
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-control-label">Event title</label>
                                    <input type="text" class="form-control form-control-alternative edit-event--title" placeholder="Event Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Starting Date</label>
                                    <input type="text" class="form-control form-control-alternative edit-event--start" placeholder="Event Start">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Ending Date</label>
                                    <input type="text" class="form-control form-control-alternative edit-event--end" placeholder="Event End">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Class</label>
                                    <input type="text" class="form-control form-control-alternative edit-event--class" placeholder="Class">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Section</label>
                                    <input type="text" class="form-control form-control-alternative edit-event--section" placeholder="Section">
                                </div>
                                <input type="hidden" class="edit-event--id">
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button class="btn btn-link ml-auto" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h3 class="h3 d-block">Events
                        <span class="pull-right float-right">
                            <a class="btn btn-info btn-sm" href="<?php echo site_url(route_to('admin.events.index')); ?>">View</a>
                        </span>
                    </h3>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Event</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Class</th>
                                <th>Section</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            $events = (new Events())
                                ->groupStart()
                                ->where("MONTH(ending_date) =",date('m'))
                                ->orWhere("MONTH(starting_date) =",date('m'))
                                ->groupEnd()
                                ->where('session',active_session())
                                ->orderBy('starting_date', 'ASC')->findAll();
                            foreach ($events as $event) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $event->name; ?></td>
                                    <td><?php echo $event->starting_date->format('d/m/Y'); ?></td>
                                    <td><?php echo $event->ending_date ? $event->ending_date->format('d/m/Y') : ''; ?></td>
                                    <td><?php echo $event->class ? $event->class->name : ''; ?></td>
                                    <td><?php echo $event->section ? $event->section->name : ''; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>