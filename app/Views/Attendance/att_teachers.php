<div class="card">
    <div class="card-header">
        <a href="<?php echo base_url('admin/attendance/teachers-pdf?year='.$year.'&month='.$month); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                    class="fa fa-cloud-download-alt"></i> PDF</a>
        <a href="<?php echo base_url('admin/attendance/teachers-print?year='.$year.'&month='.$month);?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                    class="fa fa-print"></i> Print</a>
    </div>
</div>
<style>
    .red{
        background: red;
    }
    .blue{
        background: blue;
    }
    .yellow{
        background: yellow;
    }
</style>
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
        <table class="table table-sm table-bordered" id="attend-table">
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
                <th>P</th>
                <th>A</th>
                <th>Ps</th>
                <th>S</th>
                <th>L</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            $attendance = new \App\Models\Attendance();
            foreach ($teachers as $teacher) {
                $att = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->trID)->get()->getRowObject();
                $n++;
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $teacher->surname.' '.$teacher->first_name.' '.$teacher->last_name; ?></td>
                    <?php
                    foreach ($dates as $date) {
                        $att = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->trID)->get()->getRowObject();
                        ?>
                        <td class="events <?php if ($att): if ($att->status == 5 && $att->option_type=='exams'):?>red <?php elseif ($att->status == 5 && $att->option_type=='weekend'):?>yellow <?php elseif ($att->status == 5 && $att->option_type=='events'):?>blue<?php endif;endif;?>">
                            <?php

                            if ($att) {
                                if ($att->status == 1):
                                    echo '<span class="text-green"><i class="fa fa-check-circle"></i></span>';
                                elseif ($att->status == 0):
                                    echo '<span class="text-danger">x</span>';
                                elseif ($att->status == 2):
                                    echo 'P';
                                elseif ($att->status == 3):
                                    echo 'S';
                                elseif ($att->status == 4):
                                    echo 'L';
                                endif;
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td><?php echo teacherInfo($teacher->trID,$month,$year)['present'];?></td>
                    <td><?php echo teacherInfo($teacher->trID,$month,$year)['absent'];?></td>
                    <td><?php echo teacherInfo($teacher->trID,$month,$year)['permission'];?></td>
                    <td><?php echo teacherInfo($teacher->trID,$month,$year)['sick'];?></td>
                    <td><?php echo teacherInfo($teacher->trID,$month,$year)['late'];?></td>
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
}?>

<script>
    $('#attend-table').dataTable({
        dom: 'Bfrtip',
        colReorder: true,
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            },
            // {
            //     extend: 'excel',
            //     exportOptions: {
            //         columns: [ 0, 1, 2, 3, 4, 5 ]
            //     }
            // },
            // {
            //     extend: 'pdf',
            //     exportOptions: {
            //         columns: [ 0, 1, 2, 3, 4, 5 ]
            //     }
            // },
            // {
            //     extend: 'print',
            //     exportOptions: {
            //         columns: [ 0, 1, 2, 3, 4, 5 ]
            //     }
            // },
        ],
    });
</script>
