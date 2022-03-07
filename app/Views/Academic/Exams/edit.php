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
<div class="card">
    <div class="card-body">
        <div class="modal fade" id="time_slot" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                    <form method="post" class="ajaxForm" loader="true" action="<?php echo site_url(route_to('admin.academic.save_time_slots')); ?>" data-parsley-validate="">
                        <input type="hidden" name="class" value="<?php echo $class->id; ?>">
                        <input type="hidden" name="exam" value="<?php echo $exam->id; ?>">
                        <div class="modal-header">
                            <h6 class="modal-title" id="modal-title-default">Time Slots</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                            foreach ($framework as $item) {
                                ?>
                                <div class="form-group">
                                    <label>Time Slot</label>
                                    <input type="text" class="form-control" name="slot[time][]" value="<?php echo $item['time']; ?>" required />
                                </div>
                                <!--                            <div class="form-group">-->
                                <!--                                <label><input type="checkbox" name="slot[break][]" --><?php //echo $item['break'] ? 'checked' : ''; ?><!-- onchange="this.checked ? this.value = 1 : this.value = 0" value="0" /> Break</label>-->
                                <!--                            </div>-->
                                <hr/>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <form method="post" class="ajaxForm" loader="true" action="<?php echo site_url(route_to('admin.academic.get_exam_schedule')); ?>" data-parsley-validate="">
            <input type="hidden" name="class" value="<?php echo $class->id; ?>">
            <input type="hidden" name="exam" value="<?php echo $exam->id; ?>">
            <input type="hidden" name="save" value="save" />
            <div class="card-header clearfix">
                <button class="btn btn-sm btn-success pull-right float-right clearfix" type="submit"> <i class="fa fa-plus-square"></i> Save Timetable</button>
                <button class="btn btn-sm btn-secondary pull-right float-right clearfix" type="button" data-toggle="modal" data-target="#time_slot"> <i class="fa fa-edit"></i> Modify Time Slots</button>
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
                                                <div class="form-group">
                                                    <input type="hidden" name="timetable[<?php echo $day->getTimestamp(); ?>][day]" value="<?php echo $day->getTimestamp(); ?>" />
                                                    <?php
                                                    if($sub) {
                                                        ?>
                                                        <input type="hidden" name="timetable[<?php echo $day->getTimestamp(); ?>][id][<?php echo $key; ?>]" value="<?php echo $sub->id; ?>" />
                                                        <?php
                                                    }
                                                    ?>

                                                    <select name="timetable[<?php echo $day->getTimestamp(); ?>][subject][<?php echo $key; ?>][]" class="form-control form-control-sm">
                                                        <option value="">Please select</option>
                                                        <option <?php echo @$xArray == 'break' ? 'selected' : ''; ?> value="break">Break</option>
                                                        <option <?php echo @$xArray == 'lunch_break' ? 'selected' : ''; ?> value="lunch_break">Lunch Break</option>
                                                        <?php
                                                        $subjects = $class->subjects();
                                                        foreach ($subjects as $subject) {
                                                            ?>
                                                            <option <?php echo @$xArray == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="form-group">
                                                <input type="hidden" name="timetable[<?php echo $day->getTimestamp(); ?>][day]" value="<?php echo $day->getTimestamp(); ?>" />
                                                <?php
                                                if($sub) {
                                                    ?>
                                                    <input type="hidden" name="timetable[<?php echo $day->getTimestamp(); ?>][id][<?php echo $key; ?>]" value="<?php echo $sub->id; ?>" />
                                                    <?php
                                                }
                                                ?>

                                                <select name="timetable[<?php echo $day->getTimestamp(); ?>][subject][<?php echo $key; ?>][]" class="form-control form-control-sm" >
                                                    <option value="">Please select</option>
                                                    <option <?php echo @$sub->subject == 'break ' ? 'selected' : ''; ?> value="break">Break</option>
                                                    <option <?php echo @$sub->subject == 'lunch_break' ? 'selected' : ''; ?> value="lunch_break">Lunch Break</option>
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
                                        <span id="adder" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i></span>
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
    <script>
        $(document).ready(function () {
            $(document).on('click', '#adder', function () {
                $(this).parent('#addable:first').find('.form-group:first').clone(true).appendTo($(this).parent());
            })
        })
    </script>
</div>