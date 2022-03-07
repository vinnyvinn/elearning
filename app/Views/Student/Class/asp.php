<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">After School Schedule</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php use App\Models\AspSchedules;

                    do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <?php
    $framework = [
        [
            'time' => "08:00 - 09:00",
            'break' => false,
            'label' => ""
        ], [
            'time' => "09:00 - 10:00",
            'break' => true,
            'label' => "Refreshment"
        ], [
            'time' => "09:00 - 10:00",
            'break' => false,
            'label' => ""
        ]
    ];
    //$framework = json_encode($framework);
    $framework = get_option('asp_timetable_framework', FALSE);
    $builder = new AspSchedules();
    $class = $student->class->id;
    $section = $student->section;
    ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">After School Program Timetable
                <!--            <a class="pull-right float-right btn btn-sm btn-success" href="-->
                <?php //echo site_url(route_to('admin.class.sections.create_asp_schedule', $section->id)); ?><!--"> <i class="fa fa-plus-square"></i> Create/Update Timetable</a>-->
            </h3>
        </div>
        <?php
        if ($framework) {
            $framework = json_decode($framework, TRUE);
            ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th>Day</th>
                        <?php
                        foreach ($framework as $time) {
                            ?>
                            <th><?php echo '<b>' . $time['time'] . '</b>'; ?></th>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $days = json_decode(get_option('school_days', json_encode(['Mon', 'Tue', 'Wed', 'Thur', 'Fri'])), true);
                    foreach ($days as $day) {
                        ?>
                        <tr>
                            <th><?php echo '<b>' . $day . '</b>'; ?></th>
                            <?php
                            foreach ($framework as $time) {
                                ?>
                                <td>
                                    <?php
                                    if ($time['break']) {
                                        echo '<b>' . $time['label'] . '</b>';
                                    } else {
                                        $sub = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time['time']])->first();
                                        if ($sub && $sub->subject) {
                                            echo $sub->subject->name;
                                        }
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
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
            <div class="card-body">
                <div class="alert alert-warning">
                    Timetable is not set up
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>