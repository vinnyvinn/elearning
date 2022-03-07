<?php
//d($exam);
//d($class);
$framework = [
    [
        'time'  => '08:00 - 10:00',
        'break' => false,
        'label' => ''
    ],
    [
        'time'  => '10:00 - 10:30',
        'break' => true,
        'label' => 'Mini Break'
    ],
    [
        'time'  => '10:30 - 12:30',
        'break' => false,
        'label' => ''
    ],
    [
        'time'  => '12:30 - 14:00',
        'break' => true,
        'label' => 'Lunch Break'
    ],
    [
        'time'  => '14:00 - 16:00',
        'break' => false,
        'label' => ''
    ],
];
$custom_f = get_option('custom_exam_timetable_'.$exam->id.'_'.$class->id, FALSE);
if($custom_f && $custom_f = json_decode($custom_f, true)) {
    $framework = $custom_f;
} else {
    $framework = json_decode(get_option('exams_schedule_framework', json_encode($framework)), true);
}
?>
<div class="card-bo">
    <div>
        <div class="card-header clearfix">
            <a class="btn btn-sm btn-warning pull-right float-right clearfix ml-1" href="<?php echo site_url(route_to('admin.academic.edit_exam_schedule', $class->id, $exam->id)); ?>"> <i class="fa fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-danger pull-right float-right clearfix ml-1" target="_blank" href="<?php echo site_url(route_to('admin.academic.exam_schedule.print', $class->id, $exam->id)); ?>"> <i class="fa fa-print"></i> Print</a>
            <a class="btn btn-sm btn-danger pull-right float-right clearfix ml-1" target="_blank" href="<?php echo site_url(route_to('admin.academic.exam_schedule.pdf', $class->id, $exam->id)); ?>"> <i class="fa fa-download"></i> Pdf</a>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>Day \ Time</th>
                    <?php
                    foreach($framework as $time) {
                        ?>
                        <th><?php echo $time['time']; ?></th>
                        <?php
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $days = ['Mon', 'Tue', 'Wed', 'Thur', 'Fri'];
                $days = json_decode(get_option('school_days', json_encode($days)), true);
                $begin = new DateTime($exam->starting_date);
                $end = new DateTime($exam->ending_date);
                $interval = DateInterval::createFromDateString('1 day');
                $end->modify('+1 day');
                $period = new DatePeriod($begin, $interval, $end);
                $days = $period;
                //dd($days);
                foreach ($days as $day) {
                    //dd($day->getTimestamp());
                    ?>
                    <tr>
                        <th><?php echo $day->format('l'); ?><br/>
                            <?php echo $day->format('m/d/Y'); ?>
                        </th>
                        <?php
                        foreach ($framework as $key=>$time) {
                            ?>
                            <td id="addable">
                                <?php
                                if(isset($time['break']) && $time['break'] == true) {
                                    echo isset($time['label']) ? $time['label'] : 'Break';
                                } else {
                                    $sub = \Config\Database::connect()->table('exams_timetable')->where(['class' => $class->id, 'day' => $day->getTimestamp(), 'time' => $key, 'exam' => $exam->id ])->get()->getFirstRow();
                                    $xArrays = @json_decode($sub->subject, true);
                                    if($xArrays && is_array($xArrays)) {
                                        foreach ($xArrays as $xArray) {
                                            ?>
                                            <div class="">
                                                <?php
                                                if(@$xArray == 'break') {
                                                    echo "Break";
                                                }elseif (@$xArray == 'lunch_break') {
                                                    echo "Lunch Break";
                                                }elseif(!empty($xArray)) {
                                                    $subjects = $class->subjects();
                                                    foreach ($subjects as $subject) {
                                                        if(@$xArray == $subject->id) {
                                                            echo $subject->name.'<br/>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="form-group">
                                            <?php
                                                $subjects = $class->subjects();
                                                foreach ($subjects as $subject) {
                                                    if(@$sub->subject == $subject->id) {
                                                        echo $subject->name.'<br/>';
                                                    }
                                                }
                                                ?>
                                        </div>
                                        <?php
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
    </div>
</div>