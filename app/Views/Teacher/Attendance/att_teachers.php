<?php
$start = $year . '-' . $month . '-01';
$end = $year . '-' . $month . '-' . date('t', strtotime($start));

$dates = [];
while (strtotime($start) <= strtotime($end)) {
    $day_num = date('d', strtotime($start));

    $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));

    $dates[] = $year . '-' . $month . '-' . $day_num;
}

if ($teachers && count($teachers) > 0) {
    ?>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <?php
                foreach ($dates as $date) {
                    ?>
                    <th><?php echo date('d', strtotime($date)); ?></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            $attendance = new \App\Models\Attendance();
            foreach ($teachers as $teacher) {
                $n++;
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $teacher->profile->name; ?></td>
                    <?php
                    foreach ($dates as $date) {
                        ?>
                        <td>
                            <?php
                            $att = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->id)->get()->getRowObject();
                            if ($att) {
                                echo @$att->status == 1 ? '<span class="text-green"><i class="fa fa-check-circle"></i></span>' : '<span class="text-danger">x</span>';
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
        <div class="alert alert-danger">
            No teachers found for this class
        </div>
    </div>
    <?php
}
