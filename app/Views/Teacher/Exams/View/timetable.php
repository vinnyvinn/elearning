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
?>
<div class="card-bo">
    <form method="post" class="ajaxForm" loader="true" action="<?php echo current_url(); ?>" data-parsley-validate="">
        <input type="hidden" name="class" value="<?php echo $class->id; ?>">
        <input type="hidden" name="exam" value="<?php echo $exam->id; ?>">
        <input type="hidden" name="save" value="save" />
        <div class="card-header clearfix">
            <button class="btn btn-sm btn-success pull-right float-right clearfix" type="submit"> <i class="fa fa-plus-square"></i> Save Timetable</button>
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
                        foreach ($framework as $time) {
                            ?>
                            <td>
                                <?php
                                if(isset($time['break']) && $time['break'] == true) {
                                    echo $time['label'];
                                } else {
                                    $sub = \Config\Database::connect()->table('exams_timetable')->where(['class' => $class->id, 'day' => $day->getTimestamp(), 'time' => $time['time'], 'exam' => $exam->id ])->get()->getFirstRow();
                                    ?>
                                <div class="form-group">
                                    <input type="hidden" name="timetable[<?php echo $day->getTimestamp(); ?>][day]" value="<?php echo $day->getTimestamp(); ?>" />
                                    <?php
                                    if($sub) {
                                        ?>
                                        <input type="hidden" name="timetable[<?php echo $day->getTimestamp(); ?>][id][<?php echo $time['time']; ?>]" value="<?php echo $sub->id; ?>" />
                                        <?php
                                    }
                                    ?>

                                    <select name="timetable[<?php echo $day->getTimestamp(); ?>][subject][<?php echo $time['time']; ?>]" class="form-control select2 form-control-sm" data-toggle="select2" >
                                        <option value="">Please select</option>
                                        <?php
                                        $subjects = $class->subjects();
                                        foreach ($subjects as $subject) {
                                            ?>
                                            <option <?php echo @$sub->subject == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                    <?php
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
    </form>
</div>