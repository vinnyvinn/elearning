<div class="card-body">
    <?php

    use App\Models\Timetable;
    use CodeIgniter\Database\BaseBuilder;

    //d($teacher);
    $teacher = $teacher;
    ?>
    <?php
    $framework = get_option('timetable_framework', FALSE);
    $builder = new Timetable();
    //$class = $section->class->id;
    ?>

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
                                    $ss = (new \App\Models\Subjectteachers())->select('subject_id')->where('teacher_id', $teacher->id)->findAll();
                                    $ss_temp = [];
                                    foreach ($ss as $s) {
                                        $ss_temp[] = $s->subject_id;
                                    }
                                    $sub = $builder->whereIn('subject', $ss_temp)->where(['day' => $day, 'time' => $time['time']])->findAll();
                                    if ($sub && is_array($sub)) {
                                        //d($sub);
                                        foreach ($sub as $item) {
                                            echo $item->subject->name;
                                            echo "<br/><b>".$item->class->name."</b>, ";
                                            echo "<b>".$item->section->name."</b>";
                                        }
                                    } else {
                                        echo '-';
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