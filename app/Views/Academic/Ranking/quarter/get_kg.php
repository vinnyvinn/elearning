<?php

$section = (new \App\Models\Sections())->find($section);
$quarter = (new \App\Models\Quarters())->find($quarter);
$subjects = $section->class->subjects;
$students = $section->students;

$students_arr = array();

foreach ($section->students as $student) {
    $total_marks = 0;
    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());

    if (count($subjects) > 0) {
        foreach ($subjects as $subject) {
            $result = $resultsModel->getQuarterTotalResultsPerSubject($quarter->id, $subject->id,$section->id);

            if (!empty($result) && is_numeric($result)) {
                if (!isset($students_arr[$student->id])) {
                    $students_arr[$student->id] = $subject->optional ==0 ? $result : 0;
                } else {
                    $students_arr[$student->id] += $subject->optional ==0 ? $result : 0;
                }
//                if (!isset($result_per_subject[$student->id.'.'.$subject->id])){
//                    $result_per_subject[$student->id.'.'.$subject->id] = $subject->optional ==0 ? $result : 0;
//                }else{
//                    $result_per_subject[$student->id.'.'.$subject->id] += $subject->optional ==0 ? $result : 0;
//                }
            }
        }
    }
}

$student_ranks = array_rank($students_arr);
$counter = 0;
$grading = (new \App\Models\Classes())->find($student->class->id);
$grading = $grading->grading ? json_decode($grading->grading) : [];
?>
<?php if (empty($grading)):?>
<h4 class="text-red">Please Ensure grading scale system for KG is set before proceeding</h4>
<?php else:?>
<div class="table-responsive">
    <table class="table datatable" id="datatable">
        <thead>
        <tr>
            <th>#</th>
            <th>Student</th>
            <?php
            if(count($subjects) > 0) {
                foreach ($subjects as $subject) {
                    if ($subject->optional == 0)
                        $counter++;
                    ?>
                    <th><?php echo $subject->name; ?></th>
                    <?php
                }
            }
            ?>
            <th>Average</th>
            <th>Total</th>
            <th>Rank</th>
            <th style="display: none"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $n = 0;
        $i = 0;

        foreach ($student_ranks as $student => $rank) {
            $n++;
            $total_marks = 0;
            $resultsModel = new \App\Libraries\YearlyResults($student, active_session());
            ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo getStudent($student); ?></td>
                <?php
                if(count($subjects) > 0) {
                    foreach ($subjects as $subject) {
                        $result = $resultsModel->getQuarterTotalResultsPerSubject($quarter->id, $subject->id);
                        ?>
                        <td>
                          <?php
                          if (!is_numeric($result)){
                              echo 'ww';
                          }
                            echo is_numeric($result) ? getScore($grading,$result) : '-';
                            if ($subject->optional ==0){
                                $i++;
                                $total_marks += is_numeric($result) ? $result : 0;
                            }

                            ?>
                        </td>
                        <?php
                    }
                }
                ?>
                <td>
                 <?php echo getScore($grading,number_format($total_marks/$counter, 2)); ?>
                </td>
                <td><?php echo number_format($total_marks, 2); ?></td>
                <td><?php echo $rank; ?></td>
                <td style="display:none;"></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>

<?php endif;?>
<script>
    $(document).ready(function () {
        $('#datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'pdf',
                },
                {
                    extend: 'print',
                },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1 ] }
            ]
        });
    })
</script>