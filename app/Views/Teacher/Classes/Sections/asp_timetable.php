<?php
$framework = [
    [
        'time'  => "08:00 - 09:00",
        'break' => false,
        'label' => ""
    ], [
        'time'  => "09:00 - 10:00",
        'break' => true,
        'label' => "Refreshment"
    ], [
        'time'  => "09:00 - 10:00",
        'break' => false,
        'label' => ""
    ]
];
$framework = get_option('asp_timetable_framework', FALSE);
$framework = json_decode($framework, TRUE);
$builder = new \App\Models\AspSchedules();
$class = $section->class->id;
?>
<div class="card">
    <?php
    if(!$framework && !is_array($framework)) {
        ?>
        <div class="card-body">
            <div class="alert alert-warning">
                Timetable daytime is not set
            </div>
        </div>
        <?php
    } else {
        ?>
        <form method="post" action="<?php echo current_url(); ?>" data-parsley-validate="">
            <input type="hidden" name="class" value="<?php echo $section->class->id; ?>">
            <input type="hidden" name="section" value="<?php echo $section->id; ?>">
            <div class="card-header clearfix">
                <button class="btn btn-sm btn-success pull-right float-right clearfix" type="submit"> <i class="fa fa-plus-square"></i> Save Timetable</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
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
                    $days = json_decode(get_option('school_days', json_encode(['Mon', 'Tue', 'Wed', 'Thur', 'Fri'])), true);
                    foreach ($days as $day) {
                        ?>
                        <tr>
                            <th><?php echo $day; ?></th>
                            <?php
                            foreach ($framework as $time) {
                                ?>
                                <td>
                                    <?php
                                    if(isset($time['break']) && $time['break'] == true) {
                                        echo $time['label'];
                                    } else {
                                        $sub = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time['time']])->first();
                                        ?>
                                        <div class="form-group">
                                            <select name="timetable[<?php echo $day; ?>][<?php echo $time['time']; ?>]" class="form-control select2 form-control-sm" >
                                                <option value="">Please select</option>
                                                <?php
                                                $subjects = $section->class->subjects();
                                                foreach ($subjects as $subject) {
                                                    ?>
                                                    <option <?php echo @$sub->subject->id == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
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
        <?php
    }
    ?>
</div>
