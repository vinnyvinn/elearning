<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6" id="navEventsCalendar">
    <div class="row">
        <div class="col-md-6">
            <?php
            $events = (new \App\Models\Events())->calendarEvents();
            $month_events = (new \App\Models\Events())->calendarEventsCurrentMonth();
            ?>
            <script>
               var schoolEvents = <?php echo json_encode($events); ?>

            </script>
            <div class="card card-calendar">
                <div class="card-header">
                    <h6 class="fullcalendar-title h2 d-inline-block mb-0">Calendar</h6>
                    <span class="float-right">
                        <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                            <i class="fas fa-angle-left"></i>
                        </a>
                        <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                            <i class="fas fa-angle-right"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">Month</a>
                        <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">Week</a>
                        <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">Day</a>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="calendar" data-toggle="calendar" id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Events</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        //d($parent->events);
                        ?>
                        <div class="table-responsive">
                            <table class="table datatable" id="">
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
                                 foreach ($month_events as $event) {
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php do_action('parent_homepage'); ?>


</div>
<script>
    var getStudentAttendance = function () {
        var month = $('#month').val();
        var year = $('#year').val();
        var student = $('#studentAttendance').val();
        if (month != '' && year != '' && student != '') {
            var d = {
                url: "<?php echo site_url(route_to('parent.attendance.students.ajax')); ?>",
                data: 'year=' + year + '&month=' + month + '&student=' + student,
                loader: true
            }
            ajaxRequest(d, function (data) {
                $('#ajaxAttendance').html(data);
            });
        } else {
            toast("Error", "Please select the Year, Month and Student", 'error');
        }
    }


</script>