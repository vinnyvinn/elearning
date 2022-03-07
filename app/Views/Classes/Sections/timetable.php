<?php
$framework = get_option('timetable_framework_'.$section->class->id, get_option('timetable_framework', FALSE));
$builder = new \App\Models\Timetable();
$class = $section->class->id;
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Main Timetable
            <a class="pull-right float-right btn btn-sm btn-success" href="<?php echo site_url(route_to('admin.class.sections.timetable_create', $section->id)); ?>"> <i class="fa fa-plus-square"></i> Create/Update Timetable</a>
        </h3>
    </div>
    <?php
    if($framework) {
        $framework = json_decode($framework, TRUE);
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>Day</th>
                    <?php
                    foreach($framework as $time) {
                        ?>
                        <th><?php echo '<b>'.$time['time'].'</b>'; ?></th>
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
                        <th><?php echo '<b>'.$day.'</b>'; ?></th>
                        <?php
                        foreach ($framework as $time) {
                            ?>
                            <td>
                                <?php
                                if($time['break']) {
                                    echo '<b>'.$time['label'].'</b>';
                                } else {
                                    $sub = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time['time']])->first();
                                    if($sub && $sub->subject) {
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
